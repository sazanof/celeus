<?php

namespace Vorkfork\Apps\Mail\IMAP;

use Doctrine\Common\Collections\ArrayCollection;

class MessageFlags {
	public const FLAGGED = 'flagged';
	public const IMPORTANT = 'important';
	public const RECENT = 'recent';
	public const DRAFT = 'draft';
	public const DELETED = 'deleted';
	public const SEEN = 'seen';
	public const ANSWERED = 'answered';
	public const SPAM = 'spam';
	public const NOT_SPAM = 'notspam';
	protected ArrayCollection $flags;

	public function __construct(array $flags) {
		$f = array_map(function($flag) {
			return ltrim($flag, '\&');
		}, $flags);
		$this->flags = new ArrayCollection($f);
	}

	/**
	 * @param array $array
	 * @return \Closure|mixed|string|null
	 */
	public function important(array $array): mixed {
		return $this->get(self::IMPORTANT, $array);
	}

	/**
	 * @return mixed
	 */
	public function flagged(): mixed {
		return $this->get(self::FLAGGED);
	}

	/**
	 * @param array $array
	 * @return mixed
	 */
	public function recent(array $array): mixed {
		return $this->get(self::RECENT, $array);
	}

	/**
	 * @param array $array
	 * @return mixed
	 */
	public function draft(array $array): mixed {
		return $this->get(self::DRAFT, $array);
	}

	/**
	 * @param array $array
	 * @return mixed
	 */
	public function deleted(array $array): mixed {
		return $this->get(self::DELETED, $array);
	}

	/**
	 * @param array $array
	 * @return mixed
	 */
	public function seen(array $array): mixed {
		return $this->get(self::SEEN, $array);
	}

	/**
	 * @return mixed
	 */
	public function answered(): mixed {
		return $this->get(self::ANSWERED);
	}

	/**
	 * @param array $array
	 * @return mixed
	 */
	public function spam(array $array): mixed {
		return $this->get(self::SPAM);
	}

	/**
	 * @return mixed
	 */
	public function notSpam(): mixed {
		return $this->get(self::NOT_SPAM);
	}

	/**
	 * @return bool
	 */
	public function isImportant(): bool {
		return $this->is(self::IMPORTANT);
	}

	/**
	 * @return bool
	 */
	public function isFlagged(): bool {
		return $this->is(self::FLAGGED);
	}

	/**
	 * @return bool
	 */
	public function isRecent(): bool {
		return $this->is(self::RECENT);
	}

	/**
	 * @return bool
	 */
	public function isDraft(): bool {
		return $this->is(self::DRAFT);
	}

	/**
	 * @return bool
	 */
	public function isDeleted(): bool {
		return $this->is(self::DELETED);
	}

	/**
	 * @return bool
	 */
	public function isSeen(): bool {
		return $this->is(self::SEEN);
	}

	/**
	 * @return bool
	 */
	public function isAnswered(): bool {
		return $this->is(self::ANSWERED);
	}

	/**
	 * @return bool
	 */
	public function isSpam(): bool {
		return $this->is(self::SPAM);
	}

	/**
	 * @param array $array
	 * @return bool
	 */
	public function isNotSpam(): bool {
		return $this->is(self::NOT_SPAM);
	}

	/**
	 * @param string $key
	 * @param array $array
	 * @return \Closure|mixed|string|null
	 */
	public function get(string $key): mixed {
		return $this->flags->get($key);
	}

	/**
	 * @param string $key
	 * @param array $array
	 * @return bool
	 */
	public function is(string $key): bool {
		return $this->flags->contains($key);
	}
}
