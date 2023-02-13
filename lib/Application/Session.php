<?php

namespace Celeus\Application;

class Session implements ISession
{
    public static function start()
    {
        if(!self::hasSession()){
            session_start();
        }
    }

    public static function hasSession(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public static function get(string $key): mixed
    {
        $root = $_SESSION;
        foreach (explode('.', $key) as $_key) {
            $root = $root[$_key];
        }
        return $root;
    }

    public static function set(string $key, $value): array
    {
        $_SESSION[$key] = $value;
        return $_SESSION;
    }

    public static function delete(string $key): array
    {
        unset($_SESSION[$key]);
    }
}