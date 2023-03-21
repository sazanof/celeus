<?php declare(strict_types=1);

namespace Locale;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Vorkfork\Core\Translator\Locale;

final class LocaleTest extends TestCase
{
    public function __construct(string $name)
    {
        try {
            $env = Dotenv::createImmutable(realpath('./'));
            $env->load();
        } catch (\Dotenv\Exception\InvalidPathException $e) {
            dump(__FILE__, $e->getMessage(), $e->getCode(), $e->getLine());
        }
        parent::__construct($name);
    }

    public function testGetTimezone(): void
    {
        $this->assertNotNull(Locale::getDefaultTimezone());
    }

    public function testGetLocale(): void
    {
        //user = \Vorkfork\Core\Models\User::repository()->findByUsername('admin');
        dd(Locale::getDefaultLocale());
        $this->assertNotNull(Locale::getDefaultLocale());
    }
}