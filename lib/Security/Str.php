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

    /**
     * @param string $subject
     * @return bool
     */
    public static function containsLetters(string $subject): bool
    {
        return self::isMatch(self::REG_ONLY_LETTERS, $subject);
    }
}