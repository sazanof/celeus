<?php

namespace Vorkfork\Database;

use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Vorkfork\Core\Exceptions\EntityAlreadyExistsException;
use Vorkfork\Core\Repositories\Repository;
use Vorkfork\Core\Translator\Translate;
use Vorkfork\DTO\BaseDto;
use Vorkfork\Serializer\JsonSerializer;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\Persistence\ObjectRepository;

#[ORM\MappedSuperclass]
#[ORM\HasLifecycleCallbacks]
abstract class Entity implements IEntity
{
	const BATCH_SIZE = 25;
	protected string $table = '';
	protected ?CustomEntityManager $em = null;
	protected static ?Entity $instance = null;
	protected ?Connection $connection = null;
	protected ?AbstractPlatform $platform = null;
	protected ?ORM\ClassMetadata $metadata = null;
	protected QueryBuilder $qb;
	protected string $className = '';
	protected ObjectRepository|Repository $repository;
	public ?ValidatorInterface $validator = null;

	protected array $fillable = [];

	/**
	 * @throws \Doctrine\ORM\Exception\MissingMappingDriverImplementation
	 * @throws \Doctrine\DBAL\Exception
	 */
	public function __construct()
	{
		$this->em = $this->em();
		$this->qb = $this->em->createQueryBuilder();
		$this->className = get_class($this);
		$this->connection = $this->em->getConnection();
		$this->metadata = $this->em->getClassMetadata($this->className);
		$this->platform = $this->connection->getDatabasePlatform();
		$this->repository = $this->em->getRepository($this->className);
		self::$instance = $this;
		return $this;
	}

	#[ORM\PreFlush]
	function validateEntity(PreFlushEventArgs $args)
	{
		$this->validate();
	}

	/**
	 * @throws \Doctrine\ORM\Exception\MissingMappingDriverImplementation
	 */
	public function em(): ?CustomEntityManager
	{
		return Database::getInstance()->getEntityManager();
	}

	public static function get(): ?Entity
	{
		if (is_null(self::$instance) || self::$instance->className !== get_class(new static())) {
			return new static();
		}
		return self::$instance;
	}

	public static function repository(): ObjectRepository|Repository|EntityRepository
	{
		$class = new static();
		return $class->repository;
	}

	protected static function initClass(array $args): Entity
	{
		$class = new static();
		$class->fromArray($args[0], $class->fillable);
		return $class;
	}


	/**
	 * Assign entity properties using an array
	 *
	 * @param array $attributes assoc array of values to assign
	 */
	public function fromArray(array $attributes, $allowedFields = array()): static
	{
		foreach ($attributes as $name => $value) {
			if (in_array($name, $allowedFields)) {
				if (property_exists($this, $name)) {
					$methodName = $this->_getSetterName($name);
					if ($methodName) {
						$this->{$methodName}($value);
					} else {
						$this->$name = $value;
					}
				}
			}
		}
		return $this;
	}

	private function createValidator()
	{
		if (!$this->validator instanceof ValidatorInterface) {
			$this->validator = Validation::createValidatorBuilder()
				->addMethodMapping('loadValidatorMetadata')
				->getValidator();
		}
	}

	public function validate()
	{
		$this->createValidator();
		$violations = $this->validator->validate($this);
		if ($violations->count() > 0) {

			throw new ValidationFailedException(Translate::t('Error when saving the profile'), $violations);
		}
	}

	/**
	 * Truncates the table
	 * @return void
	 */
	public function truncate(): void
	{
		$this->em->beginTransaction();
		try {
			$this->em->getConnection()
				->executeQuery('SET FOREIGN_KEY_CHECKS=0');
			$q = $this->em->getConnection()
				->getDatabasePlatform()
				->getTruncateTableSql(
					$this->metadata->getTableName()
				);
			$this->connection->executeQuery($q);
			$this->connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');
			$this->connection->commit();
		} catch (\Exception) {
			$this->em->rollback();
		}
	}

	/**
	 * Get property setter method name (if exists)
	 *
	 * @param string $propertyName entity property name
	 * @return false|string
	 */
	protected function _getSetterName(string $propertyName): bool|string
	{
		$prefixes = array('add', 'set');

		foreach ($prefixes as $prefix) {
			$methodName = sprintf('%s%s', $prefix, ucfirst(strtolower($propertyName)));

			if (method_exists($this, $methodName)) {
				return $methodName;
			}
		}
		return false;
	}

	public function toJSON(): string
	{
		return JsonSerializer::serializeStatic($this);
	}

	public function toDto(string $dtoClass): BaseDto
	{
		return JsonSerializer::deserializeStatic($this->toJSON(), $dtoClass);
	}

	public function toDtoArray(string $class): BaseDto
	{
		return JsonSerializer::deserializeStatic($this->toJSON(), $class);
	}

	/**
	 * Check if Entity already exists
	 *
	 * @param array $fields
	 * @param LifecycleEventArgs $args
	 * @return void
	 * @throws EntityAlreadyExistsException
	 */
	protected function checkExistingRecords(array $fields, LifecycleEventArgs $args): void
	{
		$obj = $args->getObject();
		$repository = $args->getObjectManager()->getRepository($obj::class)->findOneBy($fields);
		if ($repository instanceof $obj) {
			throw new EntityAlreadyExistsException($obj::class);
		}
	}

	/**
	 * @inheritDoc
	 */
	public static function find(int $id): Entity
	{
		$class = self::get();
		return $class->em->find($class->className, $id);
	}

	/**
	 * @inheritDoc
	 */
	public static function create(): static
	{
		$args = func_get_args();
		$class = self::initClass($args);

		if (!is_null($class->em)) {
			try {
				$class->em->persist($class);
				$class->em->flush();
				return $class;
			} catch (EntityAlreadyExistsException $e) {
				$class->em->detach($class);
				$class->em->clear();
				//return false;
			} catch (ORMException|OptimisticLockException $e) {
				//return false;
			}
		}
		return $class;
	}

	/**
	 * @inheritDoc
	 */
	public function update(array $arguments): static
	{
		$this->className = get_class($this);
		$this->fromArray($arguments, $this->fillable);
		$this->em()->persist($this);
		$this->em()->flush($this);
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public static function updateStaticByID(int $id, array $arguments): static
	{
		$class = self::get();
		return $class->em->find($class->className, $id)->update($arguments);
	}

	/**
	 * @throws OptimisticLockException
	 * @throws ORMException
	 * @throws MappingException
	 */
	public static function insertBulk(array $data, $batchSize = self::BATCH_SIZE)
	{
		$i = 1;
		$em = Database::getInstance()->getEntityManager();
		foreach ($data as $item) {
			$class = self::initClass([$item]);
			$class->em->persist($class);
			if (($i % $batchSize) === 0) {
				$em->flush();
				$em->clear(); // Detaches all objects from Doctrine!
			}
		}
		$em->flush(); // Persist objects that did not make up an entire batch
		$em->clear();
	}

	public function getFillable()
	{
		return $this->fillable;
	}
}