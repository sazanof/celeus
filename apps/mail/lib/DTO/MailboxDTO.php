<?php

namespace Vorkfork\Apps\Mail\DTO;

use Doctrine\Common\Collections\Collection;
use Vorkfork\Apps\Mail\IMAP\DTO\MailboxImapDTO;
use Vorkfork\Apps\Mail\Models\Account;
use Vorkfork\Serializer\JsonSerializer;

class MailboxDTO
{
	public int $id;

	public int $account;

	public int $uidValidity;

	public int $total;

	public int $unseen;

	public string $path;

	public string $name;

	public array $attributes;

	public array $children;

	/**
	 * @param mixed $children
	 */
	public function setChildren(array|Collection $children): void
	{
		$this->children = JsonSerializer::deserializeArrayStatic($children, MailboxDTO::class);
	}

}
