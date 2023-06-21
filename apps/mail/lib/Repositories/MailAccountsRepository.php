<?php

namespace Vorkfork\Apps\Mail\Repositories;

use Vorkfork\Core\Repositories\Repository;

class MailAccountsRepository extends Repository
{
	public function getUserAccountByEmail(string $email, string $username): array
	{
		return $this->findBy(['email' => $email, 'user' => $username]);
	}

	public function getAccountsByEmail(string $email): array
	{
		return $this->findBy(['email' => $email]);
	}

	public function getAccountsByUsername(string $username): array
	{
		return $this->findBy(['user' => $username]);
	}
}