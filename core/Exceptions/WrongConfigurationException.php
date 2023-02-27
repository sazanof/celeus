<?php

namespace Vorkfork\Core\Exceptions;

class WrongConfigurationException extends CustomPageException
{
    public function __construct(string $message = "System configuration error. Perhaps the system is installed incorrectly", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}