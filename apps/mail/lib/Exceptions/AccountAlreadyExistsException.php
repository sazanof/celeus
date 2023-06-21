<?php

namespace Vorkfork\Apps\Mail\Exceptions;

class AccountAlreadyExistsException extends \Exception
{
	public function __construct(string $message = "Account already exists in user's account list", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}