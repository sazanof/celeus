<?php

namespace Vorkfork\Apps\Mail\Collections;

use Vorkfork\Apps\Mail\DTO\AccountDto;
use Vorkfork\Apps\Mail\DTO\MailboxDTO;
use Vorkfork\Apps\Mail\Models\Account;
use Vorkfork\Serializer\JsonSerializer;

class AccountCollection
{
	protected const DTO_CLASS = AccountDto::class;

	/**
	 * Get User accounts with mailboxes as DTO
	 * @param string $username
	 * @return AccountDto[]
	 */
	public static function getUserAccounts(string $username): array
	{
		return JsonSerializer::deserializeArrayStatic(
			Account::repository()->getAccountsByUsername($username), self::DTO_CLASS
		);
	}
}
