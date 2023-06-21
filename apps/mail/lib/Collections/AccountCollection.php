<?php

namespace Vorkfork\Apps\Mail\Collections;

use Vorkfork\Apps\Mail\DTO\AccountDto;
use Vorkfork\Apps\Mail\Models\Account;
use Vorkfork\Serializer\JsonSerializer;

class AccountCollection
{
	protected const DTO_CLASS = AccountDto::class;

	public static function getUserAccounts(string $username)
	{
		return JsonSerializer::deserializeArrayStatic(Account::repository()->getAccountsByUsername($username), self::DTO_CLASS);
	}
}