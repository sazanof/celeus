<?php

namespace Celeus\Core\Exceptions;

class AutoloadMapNotFoundException extends CustomPageException
{
    public function __construct(string $message = "Can not autoload class map!", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}