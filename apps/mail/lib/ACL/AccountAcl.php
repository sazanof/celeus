<?php

namespace Vorkfork\Apps\Mail\ACL;

use Vorkfork\Apps\Mail\Models\Account;
use Vorkfork\Auth\Auth;

class AccountAcl
{
	public static function belongsToAuthenticatedUser(Account $account): bool
	{
		return $account->getUser() === Auth::user()->username;
	}
}