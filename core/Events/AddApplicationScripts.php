<?php

namespace Vorkfork\Core\Events;

use ArrayObject;
use Symfony\Contracts\EventDispatcher\Event;

class AddApplicationScripts extends Event
{
	public const NAME = 'add.build.js';

	protected array $scripts;

	public function __construct(array $scripts)
	{
		$this->scripts = $scripts;
	}

	public function getScripts()
	{
		return $this->scripts;
	}
}