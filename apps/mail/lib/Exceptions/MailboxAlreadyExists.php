<?php

namespace Vorkfork\Apps\Mail\Exceptions;

class MailboxAlreadyExists extends \Exception
{
	public function __construct(string $name = "", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct("Target mailbox {$name} already exists", $code, $previous);
	}
}