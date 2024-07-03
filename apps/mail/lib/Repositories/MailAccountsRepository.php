<?php

namespace Vorkfork\Apps\Mail\Repositories;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Vorkfork\Apps\Mail\DTO\AccountDto;
use Vorkfork\Apps\Mail\DTO\AccountSettingsDto;
use Vorkfork\Apps\Mail\Exceptions\AccountAlreadyExistsException;
use Vorkfork\Apps\Mail\Exceptions\AccountException;
use Vorkfork\Apps\Mail\Models\Account;
use Vorkfork\Auth\Auth;
use Vorkfork\Core\Repositories\Repository;
use Vorkfork\DTO\BaseDto;

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

	/**
	 * @param int $id
	 * @return Account
	 * @throws ORMException
	 * @throws OptimisticLockException|AccountException
	 */
	public function getAccountsById(int $id): Account
	{
		$user = Auth::user();
		$account = Account::find($id);
		if ($user->username === $account->getUser()) {
			return $account;
		}
		throw  new AccountException();

	}

	public function getAccountsByUsername(string $username): array
	{
		return $this->findBy(['user' => $username]);
	}
}
