<?php

namespace Vorkfork\Apps\Mail\DTO;

use Doctrine\Common\Collections\Collection;
use Sabre\VObject\Property\VCard\DateTime;
use Vorkfork\DTO\BaseDto;
use Vorkfork\Serializer\JsonSerializer;

class AccountDto extends BaseDto
{
	public int $id;

	public string $user;

	public string $email;

	public string $name;

	public bool $isDefault;

	public mixed $mailboxes;

	public ?DateTime $lastSync = null;

	/**
	 * @param mixed $mailboxes
	 */
	public function setMailboxes(mixed $mailboxes): void
	{
		$this->mailboxes = JsonSerializer::deserializeArrayStatic($mailboxes, MailboxDTO::class);
	}

	/**
	 * @param bool $isDefault
	 */
	public function setIsDefault(mixed $isDefault): void
	{
		$this->isDefault = (bool)$isDefault;
	}

}
