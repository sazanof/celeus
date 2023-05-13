<?php

namespace Vorkfork\DTO;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Vorkfork\Application\UserManager;

class UserDto extends BaseDto
{

	public int $id;

	public string $username;

	public string $email;

	public string $firstname;

	public string $lastname;

	public string $organization;

	public string $position;

	public string $language;

	public ?string $phone;
	
	public ?string $photo;

	public string $about;

	public array $groups;

	public static function setGroups()
	{
		return [1, 2];
	}

	/**
	 * @throws OptimisticLockException
	 * @throws TransactionRequiredException
	 * @throws ORMException
	 */
	public function getUserManager()
	{
		return UserManager::getManagerStatic($this->id);
	}

}