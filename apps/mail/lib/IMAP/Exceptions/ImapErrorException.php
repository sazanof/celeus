<?php

namespace Vorkfork\Apps\Mail\IMAP\Exceptions;

class ImapErrorException extends \Exception
{
	public function __construct(string $error, int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($error, $code, $previous);
	}
}