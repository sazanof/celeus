<?php

namespace Celeus\Application;

interface ISession
{
    public static function start();

    public static function hasSession(): bool;

    public static function get(string $key): mixed;

    public static function set(string $key, $value): array;

    /**
     * Pushed a new value to session item array
     * @param string $key
     * @param $value
     * @return array
     */

    public static function delete(string $key): array;
}