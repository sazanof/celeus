<?php

namespace Celeus\Core\Exceptions;

class EntityManagerNotDefinedException extends CustomPageException
{
    public function __construct(string $message = "Entity manager not defined", int $code = 11, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}