<?php
declare(strict_types=1);
namespace Security;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Vorkfork\Core\Models\User;
use Vorkfork\Security\PasswordHasher;

class PasswordVerifyTest extends TestCase
{
    private const PWD = 'mYTestPassword';

    public function __construct(string $name)
    {
        try {
            $env = Dotenv::createImmutable(realpath('./'));
            $env->load();
        } catch (\Dotenv\Exception\InvalidPathException $e) {
            dump(__FILE__,$e->getMessage(), $e->getCode(), $e->getLine());
        }
        parent::__construct($name);
    }

    public function testVerifyUserPassword(){
        $user = new User();
        $user->setPassword(self::PWD);
        $this->assertTrue(PasswordHasher::validate($user->getPassword(), self::PWD));
    }
}