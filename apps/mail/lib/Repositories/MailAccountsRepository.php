<?php

namespace Vorkfork\Apps\Mail\Repositories;

use Vorkfork\Core\Repositories\Repository;

class MailAccountsRepository extends Repository
{

	public function getAccountsByUsername(string $username)
	{
		return $this->findBy(['user' => $username]);
	}
}