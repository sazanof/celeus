<?php

namespace Celeus\Core\Exceptions;

class EntityAlreadyExistsException extends \Exception
{
    public function __construct(string $class = "", ?\Throwable $previous = null)
    {
        parent::__construct("Entity {$class} already exists", 9, $previous);
    }
}