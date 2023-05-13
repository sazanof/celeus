<?php

namespace Vorkfork\Security;

use Composer\Pcre\Preg;
use Composer\Pcre\Regex;

class Str
{
	protected const REG_ONLY_LETTERS = '{^[a-zA-Z]*$}';

	/**
	 * @param string $pattern
	 * @param string $subject
	 * @return bool
	 */
	public static function isMatch(string $pattern, string $subject): bool
	{
		return Regex::isMatch($pattern, $subject);
	}

	/**
	 * @param string $subject
	 * @return bool
	 */
	public static function containsLetters(string $subject): bool
	{
		return self::isMatch(self::REG_ONLY_LETTERS, $subject);
	}

	/**
	 * @param string $value
	 * @param string $search
	 * @return string
	 */
	public static function trimStart(string $value, string $search): string
	{
		if (self::startWith($value, $search)) {
			return ltrim($value, $search);
		}
		return $value;
	}

	/**
	 * @param string $value
	 * @param string $search
	 * @return string
	 */
	public static function trimEnd(string $value, string $search): string
	{
		if (str_ends_with($value, $search)) {
			return rtrim($value, $search);
		}
	}

	/**
	 * @param string $str
	 * @param string $search
	 * @return bool
	 */
	public static function startWith(string $str, string $search): bool
	{
		return str_starts_with($search, $str);
	}
}