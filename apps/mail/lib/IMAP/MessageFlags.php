<?php

namespace Vorkfork\Apps\Mail\IMAP;

use Webklex\PHPIMAP\Support\FlagCollection;

class MessageFlags
{
	public const FLAGGED = 'flagged';
	public const IMPORTANT = 'important';
	public const RECENT = 'recent';
	public const DRAFT = 'draft';
	public const DELETED = 'deleted';
	public const SEEN = 'seen';
	public const ANSWERED = 'answered';
	public const SPAM = 'spam';
	public const NOT_SPAM = 'notspam';

	/**
	 * @param FlagCollection $flagCollection
	 * @return \Closure|mixed|string|null
	 */
	public static function important(FlagCollection $flagCollection): mixed
	{
		return self::get(self::IMPORTANT, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return \Closure|mixed|string|null
	 */
	public static function flagged(FlagCollection $flagCollection): mixed
	{
		return self::get(self::FLAGGED, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return \Closure|mixed|string|null
	 */
	public static function recent(FlagCollection $flagCollection): mixed
	{
		return self::get(self::RECENT, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return \Closure|mixed|string|null
	 */
	public static function draft(FlagCollection $flagCollection): mixed
	{
		return self::get(self::DRAFT, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return \Closure|mixed|string|null
	 */
	public static function deleted(FlagCollection $flagCollection): mixed
	{
		return self::get(self::DELETED, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return \Closure|mixed|string|null
	 */
	public static function seen(FlagCollection $flagCollection): mixed
	{
		return self::get(self::SEEN, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return \Closure|mixed|string|null
	 */
	public static function answered(FlagCollection $flagCollection): mixed
	{
		return self::get(self::ANSWERED, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return \Closure|mixed|string|null
	 */
	public static function spam(FlagCollection $flagCollection): mixed
	{
		return self::get(self::SPAM, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return \Closure|mixed|string|null
	 */
	public static function notSpam(FlagCollection $flagCollection): mixed
	{
		return self::get(self::NOT_SPAM, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return bool
	 */
	public static function isImportant(FlagCollection $flagCollection): bool
	{
		return self::is(self::IMPORTANT, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return bool
	 */
	public static function isFlagged(FlagCollection $flagCollection): bool
	{
		return self::is(self::FLAGGED, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return bool
	 */
	public static function isRecent(FlagCollection $flagCollection): bool
	{
		return self::is(self::RECENT, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return bool
	 */
	public static function isDraft(FlagCollection $flagCollection): bool
	{
		return self::is(self::DRAFT, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return bool
	 */
	public static function isDeleted(FlagCollection $flagCollection): bool
	{
		return self::is(self::DELETED, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return bool
	 */
	public static function isSeen(FlagCollection $flagCollection): bool
	{
		return self::is(self::SEEN, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return bool
	 */
	public static function isAnswered(FlagCollection $flagCollection): bool
	{
		return self::is(self::ANSWERED, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return bool
	 */
	public static function isSpam(FlagCollection $flagCollection): bool
	{
		return self::is(self::SPAM, $flagCollection);
	}

	/**
	 * @param FlagCollection $flagCollection
	 * @return bool
	 */
	public static function isNotSpam(FlagCollection $flagCollection): bool
	{
		return self::is(self::NOT_SPAM, $flagCollection);
	}

	/**
	 * @param string $key
	 * @param FlagCollection $flagCollection
	 * @return \Closure|mixed|string|null
	 */
	public static function get(string $key, FlagCollection $flagCollection): mixed
	{
		return $flagCollection->get($key);
	}

	/**
	 * @param string $key
	 * @param FlagCollection $flagCollection
	 * @return bool
	 */
	public static function is(string $key, FlagCollection $flagCollection): bool
	{
		return $flagCollection->has($key);
	}
}