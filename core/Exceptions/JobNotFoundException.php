<?php

namespace Vorkfork\Core\Exceptions;

class JobNotFoundException extends \Exception
{
	public function __construct(string $message = "Job not found", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
