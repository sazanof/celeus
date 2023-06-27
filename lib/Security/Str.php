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

	public static function replace(string $pattern, string $replacement, string $subject, int $limit = -1)
	{
		return Preg::replace($pattern, $replacement, $subject, $limit);
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
		return '';
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

	/**
	 * @param $str
	 * @return string
	 */
	public static function ucfirst($str): string
	{
		return ucfirst($str);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function stripTags(string $string): string
	{
		$text = strip_tags($string, "<style>");

		$substring = substr($string, strpos($string, "<style"), strpos($text, "</style>") + 2);

		$string = str_replace($substring, "", $string);
		$string = str_replace(array("\t", "\r", "\n"), "", $string);
		return trim($string);
	}

	/**
	 * @param string $string
	 * @param int $length
	 * @param string $ending
	 * @return string
	 */
	public static function truncate(string $string, int $length = 250, string $ending = "...")
	{
		return (strlen($string) > $length) ? mb_substr($string, 0, $length - strlen($ending)) . $ending : $string;
	}
}
