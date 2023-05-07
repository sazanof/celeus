<?php

namespace Vorkfork\Apps\Mail\Controllers;

use Vorkfork\Apps\Mail\Models\Account;
use Vorkfork\Auth\Auth;
use Vorkfork\Core\Controllers\Controller;

class MailController extends Controller
{
	public function loadAccounts()
	{
		$user = Auth::user();
		dd(Account::repository()->getAccountsByUsername($user->username));
		return Auth::user();
	}
}