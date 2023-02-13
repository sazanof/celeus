<?php

namespace Celeus\Security;

interface IPasswordHasher
{
    public static function hash(string $password): string;

    public static function validate(string $password): bool;
}