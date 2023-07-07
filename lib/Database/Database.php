<?php

namespace Vorkfork\Database;

use Vorkfork\Core\Config\Config;
use Vorkfork\Core\Config\DatabaseConfig;
use Vorkfork\Core\Events\TableCollation;
use Vorkfork\Core\Events\TableListener;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\ORMSetup;

class Database implements IDatabase
{
	protected Config $config;
	protected Configuration $configuration;
	public ?Connection $connection = null;
	protected static ?Database $instance = null;
	protected ?EntityManager $entityManager = null;

	/**
	 * @throws \Doctrine\DBAL\Exception
	 */
	public function __construct(Config $config = null)
	{
		$this->config = is_null($config) ? new DatabaseConfig() : $config;
		$this->connection = $this->connect();
		self::$instance = $this;
		//dump($this->config, $this->connection);
	}

	public static function getInstance(): Database
	{
		if (is_null(self::$instance)) {
			//dump('no database instance');
			self::$instance = (new self());
		}
		return self::$instance;
	}

	public function getConfig(): array
	{
		return $this->config->getConfig();
	}

	/**
	 * @return Connection|null
	 * @throws \Doctrine\DBAL\Exception
	 */
	public function connect(): Connection|null
	{
		$config = ORMSetup::createAttributeMetadataConfiguration(
			paths: array(realpath('../core/Models')),
			isDevMode: true,
		);
		$this->configuration = $config;

		$evm = new EventManager();
		$tableListener = new TableListener(
			prefix: $this->config->getConfigValue('prefix'),
			charset: $this->config->getConfigValue('charset'),
			options: $this->config->getConfigValue('options')
		);
		$evm->addEventListener(Events::loadClassMetadata, $tableListener);

		try {
			return DriverManager::getConnection($this->config->getConfig(), $config, $evm);
		} catch (\Doctrine\DBAL\Exception $e) {
			return null;
		}

	}

	/**
	 * @throws \Doctrine\ORM\Exception\MissingMappingDriverImplementation
	 */
	public function getEntityManager(): CustomEntityManager|null
	{
		if (!is_null($this->connection) && is_null($this->entityManager)) {
			$this->entityManager = new CustomEntityManager($this->connection, $this->configuration);
		} else if (!$this->entityManager->isOpen()) {
			$this->entityManager = new CustomEntityManager($this->connection, $this->configuration);
		}

		return $this->entityManager;
	}

	public function chooseDriver(): IDatabase
	{
		// TODO: Implement chooseDriver() method.
	}
}
