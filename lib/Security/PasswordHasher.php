<?php
namespace Vorkfork\Security;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class PasswordHasher implements IPasswordHasher {

    protected static ?PasswordHasher $instance = null;
    protected ?PasswordHasherInterface $hasher = null;
    protected ?PasswordHasherFactory $factory = null;

    public  function __construct(){
        $this->factory = new PasswordHasherFactory([
            'bcrypt' => ['algorithm' => 'bcrypt'],
            'sodium' => ['algorithm' => 'sodium'],
        ]);
        $this->hasher = $this->factory->getPasswordHasher('sodium');
        self::$instance = $this;
    }

    /**
     * @return PasswordHasher
     */
    public static function getInstance(): PasswordHasher
    {
        return is_null(self::$instance) ? new self() :self::$instance;
    }

    public static function hash(string $password): string
    {
        return self::getInstance()->hasher->hash($password);
    }

    public static function validate(string $password): bool
    {
        return self::validate($password);
    }
}