<?php

namespace API\Exception;

use Throwable;

abstract class Exception extends \Exception
{
    private int $httpCode;
    use ErrorCode;

    public function __construct(int $code, ?string $message = null, ?int $httpCode = null, ?Throwable $previous = null)
    {
        $message = $message ?? $this->errorCode[$code]['message'];
        $this->httpCode = $httpCode ?? $this->errorCode[$code]['httpCode'];
        parent::__construct($message, $code, $previous);
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}