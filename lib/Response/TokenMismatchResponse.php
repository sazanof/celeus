<?php

namespace Vorkfork\Response;

use Symfony\Component\HttpFoundation\Response;

class TokenMismatchResponse extends Response
{
    public function __construct()
    {
        return parent::__construct('CSRF token mismatch', 419, []);
    }
}