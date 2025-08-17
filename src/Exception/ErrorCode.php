<?php

namespace API\Exception;

trait ErrorCode
{
    private array $errorCode = [
        # Server Error
        -3 => ['message' => 'Method `$m` class `$c` not implemented.', 'httpCode' => 501],
        -2 => ['message' => 'Class `$c` not implemented.', 'httpCode' => 501],
        -1 => ['message' => 'Internal Server Error.', 'httpCode' => 500],

        # Router Error
        1 => ['message' => 'Invalid URI.', 'httpCode' => 400],
        2 => ['message' => 'Invalid or not exist object.', 'httpCode' => 404],
        3 => ['message' => 'Invalid or not exist method for object.', 'httpCode' => 404],
        4 => ['message' => 'Invalid request method.', 'httpCode' => 405]
    ];
}