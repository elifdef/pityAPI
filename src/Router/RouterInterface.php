<?php

namespace API\Router;

interface RouterInterface
{
    public function dispatch(string $requestURI, string $requestMethod);

    public function createObject(string $objectName, string $className);
}