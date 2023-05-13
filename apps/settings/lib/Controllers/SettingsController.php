<?php

namespace Vorkfork\Apps\Settings\Controllers;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;
use Vorkfork\Core\Controllers\Controller;
use Vorkfork\Core\Events\FillDatabaseAfterInstallEvent;
use Symfony\Component\HttpFoundation\Request;
use Vorkfork\Core\Models\User;
use Vorkfork\DTO\UserDto;
use Vorkfork\File\Avatar;
use Vorkfork\File\PersonalStorage;
use Vorkfork\File\Storage;
use Vorkfork\Security\PasswordValidator;

class SettingsController extends Controller
{
	/**
	 * @throws OptimisticLockException
	 * @throws \Throwable
	 * @throws ORMException
	 * @throws TransactionRequiredException
	 */
	public function saveProfile(Request $request): \Vorkfork\DTO\BaseDto
	{
		$fields = $request->toArray();
		$id = $fields['id'];
		unset($fields['id']);
		unset($fields['groups']);

		return User::updateStaticByID($id, $fields)->toDto(UserDto::class);
	}

	public function saveProfilePhoto(Request $request): UserDto
	{
		return $this->user->getUserManager()->setAvatar($request);
	}
}