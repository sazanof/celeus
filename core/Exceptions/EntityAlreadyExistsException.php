<?php

namespace Vorkfork\Core\Exceptions;

class EntityAlreadyExistsException extends \Exception
{
	public function __construct(string $class = "", int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct("Entity {$class} already exists", $code, $previous);
	}
}