<?php

namespace API\Router;

use API\Exception\AuthError;
use API\Exception\BackendError;
use API\Exception\InvalidRoute;
use PDOException;
use TypeError;

class Router implements RouterInterface
{
    # Те що отримали від сервера: URL і HTTP метод.
    private string $requestURI;
    private string $requestMethod;

    # Для зручності виділяємо дві окремі змінні, щоб зберегти отриманий об'єкт і метод
    private string $objectURI;
    private string $methodURI;

    # Зареєстровані маршрути де вказано все ще потрібно
    private array $routes;

    public function dispatch(string $requestURI, string $requestMethod): void
    {
        try
        {
            $this->requestURI = $requestURI;
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

    public function createObject(string $objectName, string $className): RouterObjectInterface
    {
        $config = new RouterObject($objectName, $className, $this);
        $this->routes[$objectName] = $config;
        return $config;
    }

    /**
     * @throws InvalidRoute
     */
    private function splitURI(): array
    {
        if (preg_match('~^/([A-Za-z]+)\.([A-Za-z]+)(?:/([^?]*))?(?:\?(.*))?$~', $this->requestURI, $matches))
        {
            return [$matches[1], $matches[2]];
        }
        throw new InvalidRoute(1);
    }

    /**
     * @throws InvalidRoute
     * @throws BackendError
     * @throws AuthError
     */
    private function route(): array
    {
        // Перевірка чи є такі об'єкти й методи, встановлений потрібний реквест метод
        !$this->correctObject() && throw new InvalidRoute(2);
        !$this->correctMethod() && throw new InvalidRoute(3);
        !$this->correctRequestMethod() && throw new InvalidRoute(4);

        // Перевірка на потрібні параметри й чи вони містять дані
        $allowed_params = $this->routes[$this->objectURI]->getAllowedParams($this->methodURI);
        $params = [];
        $this->paramsIsset($params, $allowed_params);

        // Перевірка чи існує такий клас і його статичний метод
        $class = $this->routes[$this->objectURI]->getClassName();
        $method = $this->methodURI;

        !$this->classIsset($class) && throw new BackendError(-2, "Class `$class` not implemented.");
        !$this->methodClassIsset($class, $method) && throw new BackendError(-3, "Method `$method` class `$class` not implemented.");

        [$jsonResponse, $httpCode] = call_user_func_array([$class, $method], [$params]);
        return [$jsonResponse, $httpCode];
    }

    private function correctObject(): bool
    {
        return key_exists($this->objectURI, $this->routes);
    }

    private function correctMethod(): bool
    {
        return key_exists($this->methodURI, $this->routes[$this->objectURI]->getMethods());
    }

    private function correctRequestMethod(): bool
    {
        return $this->requestMethod === $this->routes[$this->objectURI]->getRequestMethod($this->methodURI);
    }

    /**
     * @throws InvalidRoute
     */
    private function paramsIsset(array &$params, array $allowedParams): void
    {
        $params = match ($this->requestMethod)
        {
            'POST', 'PUT', 'PATCH', 'DELETE' => $GLOBALS['request']->getPOST(true),
            'GET' => $GLOBALS['request']->getGET()
        };

        foreach ($allowedParams as $key => $extra)
        {
            $isFile = isset($extra['isFile']);
            $required = $extra['required'];
            $typeParam = $extra['type'];

            if ($isFile)
            {
                $params = array_merge($params, $GLOBALS['request']->getFILES());
            }

            $hasParam = array_key_exists($key, $params);
            $value = $hasParam ? $params[$key] : null;
            $valueType = gettype($value);

            // 1. Якщо параметра немає, але він required → помилка
            if (!$hasParam && $required)
            {
                throw new InvalidRoute(6, "Missing required param `$key`.");
            }

            // 2. Якщо параметра немає, але він optional → ОК
            if (!$hasParam && !$required)
            {
                continue;
            }

            // 3. Якщо тип параметра однаковий з указаним → помилка
            if ($valueType !== $typeParam)
            {
                throw new InvalidRoute(8, "Param `$key` has `$typeParam` type. `$valueType` received.");
            }

            // 4. Якщо параметр є, але він пустий ('' або null), і він required → помилка
            if (($value === '' || $value === null) && $required)
            {
                throw new InvalidRoute(7, "Param `$key` can't be empty.");
            }

            // 5. Якщо параметр є, але він пустий, і він optional → ОК
            // нічого робити не треба
        }
    }

    private function classIsset(string $class): bool
    {
        return class_exists($class);
    }

    private function methodClassIsset(string $class, string $method): bool
    {
        return method_exists($class, $method);
    }
}