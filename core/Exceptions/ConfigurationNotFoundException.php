<?php

namespace Celeus\Core\Exceptions;

class ConfigurationNotFoundException extends \Exception
{
    public function __construct(int $code = 0, \Throwable $previous = null)
    {
        parent::__construct('Configuration not found!', $code, $previous);
    }
}