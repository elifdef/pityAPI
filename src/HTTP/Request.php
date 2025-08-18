<?php

namespace API\HTTP;

use API\Exception\InvalidRoute;

readonly class Request
{
    public function __construct(
        public string $post,
        public array  $get,
        public array  $server,
        public array  $files)
    {
    }

    public static function Init(): static
    {
        return new static(
            file_get_contents('php://input'),
            $_GET,
            $_SERVER,
            $_FILES);
    }

    public function URI(): string
    {
        return $this->server['REQUEST_URI'];
    }

    public function method(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    /**
     * @throws InvalidRoute
     */
    public function getPostObject(): object
    {
        return json_decode($this->post) ?? throw new InvalidRoute(5);
    }

    /**
     * @throws InvalidRoute
     */
    public function getPostArray(): array
    {
        return json_decode($this->post, true) ?? throw new InvalidRoute(5);
    }
}