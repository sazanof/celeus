<?php

namespace Vorkfork\Apps\Mail\Exceptions;

class MailboxAuthenticateException extends \Exception
{
	public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct("Failed to log in to this mailbox: " . $message, $code, $previous);
	}
}
