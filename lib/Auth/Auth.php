<?php
declare(strict_types=1);

namespace Vorkfork\Auth;

use Vorkfork\Application\Session;
use Vorkfork\Core\Exceptions\UserNotFoundException;
use Vorkfork\Core\Models\User;
use Vorkfork\DTO\GroupDto;
use Vorkfork\DTO\UserDto;
use Vorkfork\Security\Acl;
use Vorkfork\Security\PasswordHasher;
use Vorkfork\Serializer\JsonSerializer;

class Auth implements IAuthenticate
{
	private const SESSION_KEY = 'vf_uid';

	protected static ?UserDto $user = null;

	protected function checkLifetime()
	{
		//TODO
	}

	private function checkSessionUid(UserDto $user): bool
	{
		return Session::get(self::SESSION_KEY) === $user->id;
	}

	/**
	 * @throws UserNotFoundException
	 */
	public static function login(string $username, string $password, bool $remember = false): ?UserDto
	{
		if (self::check($username, $password)) {
			self::$user = Auth::user();
			Session::set(self::SESSION_KEY, self::$user->id);
			return self::$user;
		}
		return null;
	}

	/**
	 * @return bool
	 */
	public static function logout(): bool
	{
		Session::delete(self::SESSION_KEY);
		return true;
	}

	/**
	 * Check if authenticate data is correct
	 * @param string $username
	 * @param string $password
	 * @return bool
	 * @throws UserNotFoundException
	 */
	public static function check(string $username, string $password): bool
	{
		$user = User::repository()->findByUsername($username);

		if (is_null($user)) {
			throw new UserNotFoundException();
		}
		if (PasswordHasher::validate($user->getPassword(), $password)) {
			self::$user = $user->toDto(UserDto::class);
			return true;
		}
		return false;
	}

	/**
	 * @return ?UserDto
	 */
	public static function getLoginUser(): ?UserDto
	{
		if (is_null(self::$user)) {
			$user = User::repository()->findById(self::getLoginUserID());
			/* @var UserDto $userDto */
			$userDto = $user->toDto(UserDto::class);
			$groups = $user->getGroups();
			$userDto->set('groups', JsonSerializer::deserializeArrayStatic($groups->toArray(), GroupDto::class));
			//$userDto->set('acl', Acl::fromGroupsCollection($groups));
			self::$user = $userDto;
		}
		return self::$user;
	}

	public static function getLoginUserID()
	{
		return Session::get(self::SESSION_KEY);
	}

	/**
	 * @return bool
	 */
	public static function isAuthenticated(): bool
	{
		if (!is_null(self::getLoginUserID())) {
			return self::getLoginUser() !== null;
		}
		return false;
	}

	/**
	 * @return UserDto|null
	 */
	public static function user(): ?UserDto
	{
		return self::getLoginUser();
	}
}