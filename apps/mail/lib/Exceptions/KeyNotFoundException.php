<?php

namespace Vorkfork\Apps\Mail\Exceptions;

class KeyNotFoundException extends \Exception
{
	public function __construct(string $message = "Encryption key not found on server. Please, read the docs.", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}