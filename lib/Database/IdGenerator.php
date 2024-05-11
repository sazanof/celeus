<?php

namespace Vorkfork\Database;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;

/**
 * Id generator that obtains IDs from special "identity" columns. These are columns
 * that automatically get a database-generated, auto-incremented identifier on INSERT.
 * This generator obtains the last insert id after such an insert.
 */
class IdGenerator extends AbstractIdGenerator
{
	/**
	 * {@inheritDoc}
	 * @throws \Doctrine\DBAL\Exception
	 */

	public function generateId(EntityManagerInterface $em, ?object $entity): mixed
	{
		$table = $em->getClassMetadata($entity::class)->getTableName();
		$maxId = $em->getConnection()->createQueryBuilder()
			->select('MAX(id)')
			->from($table)
			->executeQuery()
			->fetchOne();
		return is_null($maxId) ? 1 : $maxId + 1;
	}
}
