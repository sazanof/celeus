<?php

use Celeus\Core\Config\Config;
use Celeus\Core\Console\Commands\TestCommand;
use Celeus\Core\Events\TablePrefix;
use Doctrine\Common\EventManager;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Dotenv\Dotenv;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Configuration\Migration\ExistingConfiguration;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Metadata\Storage\TableMetadataStorageConfiguration;
use Doctrine\Migrations\Tools\Console\Command as DoctrineCommand;
require_once realpath(dirname(__FILE__,2)) . '/vendor/autoload.php';
const Celeus_CONSOLE = 1;
$env = Dotenv::createImmutable(realpath('./'));
$env->load();


//$application = new Application();
//$application->setName('Celeus Console');

$config = new Config('database');
$connection = DriverManager::getConnection($config->getConfig());
$configuration = new Configuration();
//TODO add applications migrations too
$configuration->addMigrationsDirectory('Clb\Migrations', 'database/migrations');
$configuration->setAllOrNothing(true);
$configuration->setCheckDatabasePlatform(false);

$storageConfiguration = new TableMetadataStorageConfiguration();
$storageConfiguration->setTableName($config->getConfigValue('prefix') . 'migrations');

$configuration->setMetadataStorageConfiguration($storageConfiguration);
$paths = [realpath('./core/Models')];
$isDevMode = env('APP_MODE') === 'development';

$ormConfig = ORMSetup::createAttributeMetadataConfiguration(
    paths: array(realpath('./core/Models')),
    isDevMode: env('APP_MODE') === 'development',
);

$evm = new EventManager;
$tablePrefix = new TablePrefix($config->getConfigValue('prefix'));
$evm->addEventListener(Events::loadClassMetadata, $tablePrefix);

$entityManager = new EntityManager($connection, $ormConfig, $evm);

$dependencyFactory = DependencyFactory::fromEntityManager(
    new ExistingConfiguration($configuration),
    new ExistingEntityManager($entityManager)
);



$commands = [
    new TestCommand(),
    new DoctrineCommand\CurrentCommand($dependencyFactory),
    new DoctrineCommand\DiffCommand($dependencyFactory),
    new DoctrineCommand\DumpSchemaCommand($dependencyFactory),
    new DoctrineCommand\ExecuteCommand($dependencyFactory),
    new DoctrineCommand\GenerateCommand($dependencyFactory),
    new DoctrineCommand\LatestCommand($dependencyFactory),
    new DoctrineCommand\ListCommand($dependencyFactory),
    new DoctrineCommand\MigrateCommand($dependencyFactory),
    new DoctrineCommand\RollupCommand($dependencyFactory),
    new DoctrineCommand\StatusCommand($dependencyFactory),
    new DoctrineCommand\SyncMetadataCommand($dependencyFactory),
    new DoctrineCommand\UpToDateCommand($dependencyFactory),
    new DoctrineCommand\VersionCommand($dependencyFactory),
];

try {
    ConsoleRunner::run(
        new SingleManagerProvider($entityManager),
        $commands
    );
} catch (Exception $e) {
    //log
}

