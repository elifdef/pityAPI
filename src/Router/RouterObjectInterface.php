<?php

namespace API\Router;

interface RouterObjectInterface
{
    public function POST(string $methodName, array $params);

    public function GET(string $methodName, array $params);
}