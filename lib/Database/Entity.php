<?php

namespace Vorkfork\Database;

use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Exception\NotSupported;
use Sabre\DAV\Exception;
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
	 * @throws NotSupported
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
		//TODO переписать с использованием аргументов
		$class = new static();
		$class->fromArray($args[0], $class->fillable);
		if (isset($args[1]) && is_callable($args[1])) {
			$args[1]($class);
		}
		self::$instance = $class;
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

	/**
	 * @return void
	 */
	public function validate(): void
	{
		$this->createValidator();
		$violations = $this->validator->validate($this);
		if ($violations->count() > 0) {
			throw new ValidationFailedException(Translate::t('Error when saving the entity'), $violations);
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
	public static function find(int $id): ?static
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
				//$class->em->clear();
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
	public function update(array $arguments, \Closure $closure = null): static
	{
		$this->className = get_class($this);
		$entity = $this->fromArray($arguments, $this->fillable);
		if (is_callable($closure)) {
			$closure($entity);
		}
		$entity->em()->persist($entity);
		$entity->em()->flush();
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
				//$em->clear(); // Detaches all objects from Doctrine!
			}
		}
		$em->flush(); // Persist objects that did not make up an entire batch
		//$em->clear();
	}

	/**
	 * Save the model
	 * @throws OptimisticLockException
	 * @throws ORMException
	 * @throws MissingMappingDriverImplementation
	 */
	public function save($refresh = false)
	{
		$this->em()->persist($this);
		$this->em()->flush();
		if ($refresh) {
			$this->refresh();
		}
		return $this;
	}

	/**
	 * Refresh the model
	 * @throws MissingMappingDriverImplementation
	 * @throws ORMException
	 * @throws \Doctrine\ORM\TransactionRequiredException
	 */
	public function refresh()
	{
		$this->em()->refresh($this);
		return $this;
	}

	/**
	 * @return void
	 * @throws MissingMappingDriverImplementation
	 * @throws ORMException
	 * @throws OptimisticLockException
	 */
	public function remove(): void
	{
		$this->em()->remove($this);
		$this->em()->flush();
	}

	public function getFillable()
	{
		return $this->fillable;
	}
}
