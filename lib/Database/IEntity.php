<?php

namespace Vorkfork\Database;

use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\TransactionRequiredException;
use Doctrine\Persistence\Mapping\MappingException;
use Throwable;

interface IEntity
{
	/**
	 * Find and return an entity
	 * @param int $id
	 * @return static
	 * @throws TransactionRequiredException
	 * @throws ORMException
	 * @throws OptimisticLockException
	 */
	public static function find(int $id): ?static;

	/**
	 * Create new Entity
	 * @return Entity
	 * @return static
	 * @throws MappingException
	 */

	public static function create(): static;

	/**
	 * Updates an Entity by id
	 * @param int $id
	 * @param array $arguments
	 * @return static
	 * @throws ORMException
	 * @throws OptimisticLockException
	 * @throws TransactionRequiredException
	 * @throws Throwable
	 */
	public static function updateStaticByID(int $id, array $arguments): static;

	/**
	 * @param array $arguments
	 * @return static
	 * @throws ORMException
	 * @throws OptimisticLockException
	 * @throws MissingMappingDriverImplementation
	 */
	public function update(array $arguments): static;

}
