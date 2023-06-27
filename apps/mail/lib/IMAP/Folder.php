<?php

namespace Vorkfork\Apps\Mail\IMAP;

use Doctrine\Common\Collections\ArrayCollection;
use Webklex\PHPIMAP\Client;

class Folder extends \Webklex\PHPIMAP\Folder
{
	protected ArrayCollection $attributes;
	protected bool $isTrash;
	protected bool $isSent;
	protected bool $isJunk;
	protected bool $isSpam;

	const TRASH = '\trash';
	const SENT = '\sent';
	const DRAFT = '\draft';
	const JUNK = '\junk';
	const SPAM = '\spam';

	public function __construct(Client $client, string $folder_name, string $delimiter, array $attributes)
	{
		parent::__construct($client, $folder_name, $delimiter, $attributes);
		$this->attributes = new ArrayCollection($attributes);
		$this->attributes = $this->attributes->map(function ($el) {
			return strtolower($el);
		});
	}

	/**
	 * @param bool $isTrash
	 */
	public function setIsTrash(bool $isTrash): void
	{
		$this->isTrash = $isTrash;
	}

	/**
	 * @param bool $isSent
	 */
	public function setIsSent(bool $isSent): void
	{
		$this->isSent = $isSent;
	}

	/**
	 * @param bool $isSpam
	 */
	public function setIsSpam(bool $isSpam): void
	{
		$this->isSpam = $isSpam;
	}

	/**
	 * @param bool $isJunk
	 */
	public function setIsJunk(bool $isJunk): void
	{
		$this->isJunk = $isJunk;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getAttributes(): ArrayCollection
	{
		return $this->attributes;
	}

	private function hasFlag($flag)
	{
		return $this->attributes->indexOf(strtolower($flag));
	}
}
