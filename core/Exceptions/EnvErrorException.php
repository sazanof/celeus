<?php

namespace Vorkfork\Core\Exceptions;

class EnvErrorException extends \Exception
{
    public function __construct($key = '', $code = 0, \Throwable $previous = null)
    {
        parent::__construct("Error on getting ENV variable by key {$key}", $code, $previous);
    }
}