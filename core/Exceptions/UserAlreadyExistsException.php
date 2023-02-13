<?php

namespace Celeus\Core\Exceptions;

use Doctrine\DBAL\Exception;

class UserAlreadyExistsException extends Exception
{
    public function __construct(string $message = "User already exists in database", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}