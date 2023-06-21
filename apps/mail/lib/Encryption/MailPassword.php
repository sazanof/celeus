<?php

namespace Vorkfork\Apps\Mail\Encryption;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\BadFormatException;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Defuse\Crypto\Key;
use Vorkfork\Apps\Mail\IMAP\Exceptions\KeyNotFoundException;

final class MailPassword
{
	/**
	 * @throws KeyNotFoundException
	 */
	public static function getKey()
	{
		$key = env('ENCRYPTION_KEY');
		if (is_null($key)) {
			throw new KeyNotFoundException();
		}
		return Key::loadFromAsciiSafeString($key);;
	}

	/**
	 * @throws EnvironmentIsBrokenException
	 * @throws KeyNotFoundException
	 */
	public static function encrypt($text): string
	{
		$key = self::getKey();
		return Crypto::encrypt($text, $key);
	}

	/**
	 * @throws KeyNotFoundException
	 * @throws EnvironmentIsBrokenException
	 * @throws WrongKeyOrModifiedCiphertextException
	 */
	public static function decrypt($cipherText): string
	{
		$key = self::getKey();
		return Crypto::decrypt($cipherText, $key);
	}
}