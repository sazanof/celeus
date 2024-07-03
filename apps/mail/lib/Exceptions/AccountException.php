<?php

namespace Vorkfork\Apps\Mail\Exceptions;

class AccountException extends \Exception
{
	public function __construct(string $message = "You have not access to account", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
