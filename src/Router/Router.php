<?php

namespace API\Router;

use API\Exception\AuthError;
use API\Exception\BackendError;
use API\Exception\InvalidRoute;
use PDOException;
use TypeError;

class Router implements RouterInterface
{
    private string $URI;
    private string $requestMethod;
    private string $objectURI;
    private string $methodURI;
    private array $registeredMethods = [];

    public function dispatch(string $requestURI, string $requestMethod): void
    {
        // TODO: Implement dispatch() method.
        try
        {
            $this->URI = $requestURI;
            $this->requestMethod = $requestMethod;
            [$this->objectURI, $this->methodURI] = $this->splitURI();
            [$jsonResponse, $httpCode] = $this->route();

            http_response_code($httpCode);
            echo $jsonResponse;
        } catch (InvalidRoute|AuthError $exception)
        {
            http_response_code($exception->getHttpCode());
            echo json_encode(
                [
                    "error" => [
                        "code" => $exception->getCode(),
                        "message" => $exception->getMessage()
                    ]
                ]
            );
        } catch (PDOException|BackendError|TypeError  $exception)
        {
            if (method_exists(get_class($exception), 'getHttpCode'))
                http_response_code($exception->getHttpCode());
            else
                http_response_code(500);
            echo json_encode(
                [
                    "error" => [
                        "code" => -1,
                        "message" => 'Internal Server Error.',
                        'debugInfo' => [
                            'message' => $exception->getMessage(),
                            'file' => $exception->getFile(),
                            'line' => $exception->getLine(),
                            'trace' => $exception->getTraceAsString()
                        ]
                    ]
                ]
            );
        }
        die;
    }

    public function register(string $fileJSON): void
    {
        $jsonConfig = json_decode(file_get_contents($fileJSON));
        $this->registeredMethods[$jsonConfig->object] = $jsonConfig->methods;
    }

    /**
     * @throws InvalidRoute
     */
    private function splitURI(): array
    {
        if (preg_match(
            '~^/([A-Za-z]+)\.([A-Za-z]+)(?:/([^?]*))?(?:\?(.*))?$~',
            $this->URI,
            $matches))
        {
            return [$matches[1], $matches[2]];
        }
        throw new InvalidRoute(1);
    }

    /**
     * @throws InvalidRoute
     * @throws BackendError
     */
    private function route(): array
    {
        global $request;
        // Перевірка чи зареєстровані такі об'єкти й методи
        $this->objectIsset();
        $this->methodIsset();

        // Перевірка чи встановлений потрібний реквест метод
        $this->requestMethodSetted();

        // Перевірка чи існує такий клас і його статичний метод
        $class = "API\\Objects\\$this->objectURI";
        $method = $this->methodURI;

        $this->classIsset($class);
        $this->methodClassIsset($class, $method);

        // Перевірка чи є ті параметри які приймає метод
        $params = match ($this->requestMethod)
        {
            'POST', 'PUT', 'PATCH', 'DELETE' => $request->getPOSTArray(),
            'GET' => $request->getGET(),
            default => 'How Did We Get Here?'
        };
        $allowed_params = $this->registeredMethods[$this->objectURI]->$method->params;
        $this->paramsIsset($params, $allowed_params);

        [$jsonResponse, $httpCode] = call_user_func_array([$class, $method], []);
        return [$jsonResponse, $httpCode];
    }

    /**
     * @throws InvalidRoute
     */
    private function objectIsset(): void
    {
        if (!key_exists($this->objectURI, $this->registeredMethods))
        {
            throw new InvalidRoute(2);
        }
    }

    /**
     * @throws InvalidRoute
     */
    private function methodIsset(): void
    {
        $method = $this->methodURI;
        if (!isset($this->registeredMethods[$this->objectURI]->$method))
        {
            throw new InvalidRoute(3);
        }
    }

    /**
     * @throws InvalidRoute
     */

    # .-- .... .- -   - .... .   .... . .-.. .-..  .-- .. - ....  -. .- -- .. -. --.  - .... .. ...  ..-. ..- -. -.-. - .. --- -. ..--.. ..--.. ..--..
    private function requestMethodSetted(): void
    {
        $method = $this->methodURI;
        $curMethod = $this->requestMethod;
        $regMethod = $this->registeredMethods[$this->objectURI]->$method->request_method;
        if ($curMethod !== $regMethod)
        {
            throw new InvalidRoute(4);
        }
    }

    /**
     * @throws BackendError
     */
    private function classIsset(string $class): void
    {
        if (!class_exists($class))
        {
            throw new BackendError(-2, "Class `$class` not implemented.");
        }
    }

    /**
     * @throws BackendError
     */
    private function methodClassIsset(string $class, string $method): void
    {
        if (!method_exists($class, $method))
        {
            throw new BackendError(-3, "Method `$method` class `$class` not implemented.");
        }
    }

    /**
     * @throws InvalidRoute
     */
    private function paramsIsset(array $params, array $allowedParams): void
    {
        $missingParams = array_diff($allowedParams, array_keys($params));

        if (!empty($missingParams))
        {
            throw new InvalidRoute(6, "Missing [" . implode(", ", $missingParams) . "] params.");
        }

        foreach ($allowedParams as $key)
        {
            if (is_string($params[$key]) && trim($params[$key]) === '')
            {
                throw new InvalidRoute(7, "Param `$key` can't be empty.");
            }

            if ($params[$key] === null)
            {
                throw new InvalidRoute(7, "Param `$key` can't be null.");
            }
        }
    }
}