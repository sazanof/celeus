<?php

namespace Vorkfork\Apps\Mail\IMAP\DTO;

use Vorkfork\Apps\Mail\IMAP\Imap;
use Vorkfork\DTO\BaseDto;

class MailboxImapDTO extends BaseDto
{
	public int $id;
	
	public string $name;

	public string $attributes;

	public string $delimiter;

	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDelimiter(): string
	{
		return $this->delimiter;
	}

	/**
	 * @return int
	 */
	public function getAttributes(): int
	{
		return $this->attributes;
	}

	/**
	 * @return array
	 */
	public function getArrayAttributes(): array
	{
		return Imap::parseAttributes($this->attributes);
	}
}