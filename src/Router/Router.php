<?php

namespace API\Router;
class Router implements RouterInterface
{
    private string $URI;
    private string $requestMethod;
    private string $objectURI;
    private string $methodURI;
    private array $registeredMethods = [];

    public function dispatch(string $requestURI, string $requestMethod)
    {
        // TODO: Implement dispatch() method.
        $this->URI = $requestURI;
        $this->requestMethod = $requestMethod;
        [$this->objectURI, $this->methodURI] = $this->splitURI();
    }

    public function register(string $fileJSON): void
    {
        $jsonConfig = json_decode(file_get_contents($fileJSON));
        $this->registeredMethods[$jsonConfig->object] = $jsonConfig->methods;
    }

    private function splitURI(): ?array
    {
        if (preg_match("/^\/([A-Za-z]+)\.([A-Za-z]+)$/", $this->URI, $matches))
        {
            return [$matches[1], $matches[2]];
        }
        return null;
    }
}