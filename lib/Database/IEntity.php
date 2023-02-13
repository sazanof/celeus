<?php

namespace Celeus\Database;

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
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     * @throws ORMException
     */
    public static function find(int $id): Entity;

    /**
     * Create new Entity
     * @return Entity
     * @throws MappingException
     */

    public static function create(): Entity;

    /**
     * Updates an Entity by id
     * @param int $id
     * @param array $arguments
     * @return Entity
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     * @throws Throwable
     */
    public static function updateStaticByID(int $id, array $arguments): Entity;

    /**
     * @param array $arguments
     * @return Entity
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws MissingMappingDriverImplementation
     */
    public function update(array $arguments): Entity;

}