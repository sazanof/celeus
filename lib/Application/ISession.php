<?php

namespace Vorkfork\Application;

interface ISession
{
    /**
     * @return void
     */
    public static function start(): void;

    /**
     * @return void
     */
    public static function save(): void;

    /**
     * @return bool
     */
    public static function hasSession(): bool;

    /**
     * @param string $key
     * @return mixed
     */
    public static function get(string $key): mixed;

    /**
     * @param string $key
     * @param $value
     * @return array
     */
    public static function set(string $key, $value): array;

    /**
     * Pushed a new value to session item array
     * @param string $key
     */

    public static function delete(string $key);
}