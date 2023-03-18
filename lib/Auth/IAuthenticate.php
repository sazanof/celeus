<?php
declare(strict_types=1);

namespace Vorkfork\Auth;

interface IAuthenticate
{
    public static function login(string $username, string $password, bool $remember = false);

    public static function logout();

    public static function check(string $username, string $password): bool;

    public static function getLoginUser();

    public static function getLoginUserID();
}