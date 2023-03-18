<?php
declare(strict_types=1);

namespace Vorkfork\Security;

interface IPasswordHasher
{
    public static function hash(string $password): string;

    public static function validate(string $hashedPassword, string $password): bool;
}