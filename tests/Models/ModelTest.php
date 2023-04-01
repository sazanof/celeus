<?php declare(strict_types=1);

namespace Models;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

final class ModelTest extends TestCase
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

	public function testFindByUsername(): void
	{
		$user = \Vorkfork\Core\Models\User::repository()->findByUsername('admin');
		$this->assertSame('admin', $user->getUsername());
	}
}