<?php

namespace Vorkfork\Core\Translator;

use IntlTimeZone;
use Vorkfork\Core\Application;
use Vorkfork\Core\Models\Config;
use Vorkfork\Database\Database;

class Locale
{
    protected static IntlTimeZone|null $intlTimeZone = null;

    private static function tz()
    {
        return is_null(self::$intlTimeZone) ? IntlTimeZone::createDefault() : self::$intlTimeZone;
    }

    public static function getDefaultTimezone(): string
    {
        $tz = Config::repository()->getTimezone();
        return is_null($tz) ? self::tz()->toDateTimeZone()->getName() : $tz->getValue();
    }

    public static function setDefaultTimezone(): string
    {
        return self::tz()->toDateTimeZone()->getName();
    }

    public static function getDefaultLocale(): mixed
    {
        if (is_null(Database::getInstance()->getEntityManager())) {
            return env('DEFAULT_LOCALE', 'en');
        }
        $locale = Config::repository()->getLocale();
        return is_null($locale) ? env('DEFAULT_LOCALE', 'en') : $locale->getValue();
    }
}