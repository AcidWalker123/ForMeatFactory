<?php

namespace App\Shared\Exception;

class ProductNotFoundException extends \RuntimeException
{
    public function __construct(string $message = "Product not found", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
