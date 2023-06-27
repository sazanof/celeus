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

	const SPECIAL_ATTRIBUTES = [
		'haschildren' => ['\haschildren'],
		'hasnochildren' => ['\hasnochildren'],
		'template' => ['\template', '\templates'],
		'inbox' => ['\inbox'],
		'sent' => ['\sent'],
		'drafts' => ['\draft', '\drafts'],
		'archive' => ['\archive', '\archives'],
		'trash' => ['\trash'],
		'junk' => ['\junk', '\spam'],
	];

	public function __construct(Client $client, string $folder_name, string $delimiter, array $attributes)
	{
		parent::__construct($client, $folder_name, $delimiter, $attributes);
		$this->attributes = new ArrayCollection();
		array_map(function ($el) {
			foreach (self::SPECIAL_ATTRIBUTES as $key => $attribute) {
				if (in_array(strtolower($el), $attribute)) {
					$this->attributes->add($key);
				}
			}
		}, $attributes);
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
