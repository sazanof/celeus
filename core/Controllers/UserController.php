<?php

namespace Vorkfork\Core\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Vorkfork\Auth\Auth;
use Vorkfork\Core\Models\User;
use Vorkfork\DTO\BaseDto;
use Vorkfork\DTO\UserDto;
use Vorkfork\File\Avatar;
use Vorkfork\File\Storage;
use Vorkfork\Graphics\Image;

class UserController extends Controller
{
	/**
	 * @return BaseDto
	 */
	public function getUser(): BaseDto
	{
		return Auth::getLoginUser();
	}

	public function getUserAvatar(string $username, int $size = 32)
	{
		$user = User::repository()->findByUsername($username);
		$userDto = $user->toDto(UserDto::class);
		$userManager = $userDto->getUserManager();
		if (!empty($user->getPhoto())) {
			if ($userManager->hasAvatar()) {
				$image = Image::open($userManager->avatarPath(true));
				$avatarFile = $userManager->getPrincipalAvatarsRootDirectory() . DIRECTORY_SEPARATOR . $size . '_' . $userDto->photo;
				if (!Storage::existsStatic($avatarFile)) {
					$croppedImage = $image->cropResize($size, $size);
					$userManager
						->getDirectory()
						->createFile(Avatar::DIRECTORY . DIRECTORY_SEPARATOR . $size . '_' . $userDto->photo, $croppedImage->get());
				} else {
					$croppedImage = Image::open($avatarFile);
				}
				//TODO get cached image::
				return Avatar::responseFromImage($croppedImage);
			}

		}
		// todo detect photo;
		if (empty($user->getFirstname()) && empty($user->getLastname())) {
			$name = $user->getUsername();
		} else {
			$name = $user->getFirstname() . ' ' . $user->getLastname();
		}
		return Avatar::getDefault($name, $size);
	}
}