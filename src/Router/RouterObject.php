<?php

namespace API\Router;

class RouterObject implements RouterObjectInterface
{
    private string $objectName;
    private string $className;
    private array $methods = [];
    private RouterInterface $router;

    public function __construct(string $objectName, string $className, RouterInterface $router)
    {
        $this->objectName = $objectName;
        $this->className = $className;
        $this->router = $router;
    }

    public function POST(string $methodName, array $params): static
    {
        // TODO: Implement POST() method.
        return $this->add('POST', $methodName, $params);
    }

    public function GET(string $methodName, array $params): static
    {
        // TODO: Implement GET() method.
        return $this->add('GET', $methodName, $params);
    }

    private function add(string $requestMethod, string $methodName, array $params): static
    {
        $this->methods[$methodName] =
            [
                'request_method' => $requestMethod,
                'allowed_params' => $params
            ];
        return $this;
    }

    public function getObjectName(): string
    {
        return $this->objectName;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getAllowedParams(string $methodName): ?array
    {
        return $this->methods[$methodName]['allowed_params'] ?? null;
    }

    public function getRequestMethod(string $methodName): ?string
    {
        return $this->methods[$methodName]['request_method'] ?? null;
    }
}