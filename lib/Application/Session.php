<?php

namespace Vorkfork\Application;

class Session implements ISession
{
	public static function start(): void
	{
		if (!self::hasSession()) {
			session_start();
		}
	}

	public static function save(): void
	{
		if (!self::hasSession()) {
			self::start();
		}
		session_write_close();
	}

	public static function hasSession(): bool
	{
		return session_status() === PHP_SESSION_ACTIVE;
	}

	public static function get(string $key): mixed
	{
		if (!self::hasSession()) {
			self::start();
		}
		return self::has($key) ? $_SESSION[$key] : null;
	}

	public static function has(string $key): mixed
	{
		return isset($_SESSION[$key]);
	}

	public static function set(string $key, $value): array
	{
		if (!self::hasSession()) {
			self::start();
		}
		$_SESSION[$key] = $value;
		return $_SESSION;
	}

	public static function delete(string $key)
	{
		if (!self::hasSession()) {
			self::start();
		}
		unset($_SESSION[$key]);
	}

	public static function destroy()
	{
		session_destroy();
	}
}