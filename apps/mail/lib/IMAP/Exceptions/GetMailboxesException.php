<?php

namespace Vorkfork\Apps\Mail\IMAP\Exceptions;

class GetMailboxesException extends \Exception
{
	public function __construct(string $message = "Error while getting mailbox", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}