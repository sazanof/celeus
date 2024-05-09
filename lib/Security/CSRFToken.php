<?php
declare(strict_types=1);

namespace Vorkfork\Security;

use Exception;
use Vorkfork\Application\Session;

class CSRFToken
{
	public const KEY = 'csrf-token';

	/**
	 * @return string
	 * @throws Exception
	 */
	public static function generate(): string
	{
		$token = self::token();
		Session::set(self::KEY, $token);
		return $token;
	}

	/**
	 * @return ?string
	 */
	public static function getToken(): ?string
	{
		return Session::get(self::KEY);
	}

	/**
	 * @throws Exception
	 */
	public static function token(): string
	{
		return bin2hex(random_bytes(32));
	}

	public static function verify(string $token): bool
	{
		return hash_equals(self::getToken(), $token);
	}
}
