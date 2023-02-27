<?php

namespace Vorkfork\Application;

use Vorkfork\Core\Application;
use Vorkfork\Core\Exceptions\AutoloadMapNotFoundException;
use Vorkfork\Core\Exceptions\EntityManagerNotDefinedException;
use Vorkfork\Core\Exceptions\WrongConfigurationException;
use Vorkfork\Core\Models\Config;
use Vorkfork\Core\Router\MainRouter;
use Vorkfork\Database\CustomEntityManager;
use Vorkfork\Database\Database;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ApplicationUtilities
{
    private string $version;
    private array $versionArray;
    private EntityManager|CustomEntityManager|null $entityManager = null;
    protected static ?ApplicationUtilities $instance = null;
    protected Database $database;
    protected EventDispatcher $dispatcher;
    protected MainRouter $router;
    protected ?array $applicationsList;

    public function __construct(){
        $Vorkfork_Version = '';
        $Vorkfork_VersionArray = [];
        if (php_sapi_name() == "cli") {
            require realpath('./inc/version.php');
        } else {
            require realpath('../inc/version.php');
        }
        $this->version = $Vorkfork_Version;
        $this->versionArray = $Vorkfork_VersionArray;
        $this->database = Database::getInstance();

        self::$instance = $this;
    }

    public function setDispatcher(EventDispatcher $dispatcher){
        $this->dispatcher = $dispatcher;
    }

    public function getDispatcher(){
        return $this->dispatcher;
    }

    public function setRouter(MainRouter $router){
        $this->router = $router;
    }

    public static function getInstance(): ApplicationUtilities
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setEntityManager(EntityManager $em): ?EntityManager
    {
        $this->entityManager = $em;
        return $this->entityManager;
    }

    public function getEntityManager(): EntityManager|CustomEntityManager|null
    {
        return $this->entityManager;
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getVersionArray(): array
    {
        return $this->versionArray;
    }

    /**
     * Gets version of any App
     * @return mixed|object
     * @throws WrongConfigurationException
     */
    public function getDatabaseAppVersion(string $key = null): mixed
    {
        $dbVersion = $this->entityManager->getRepository(Config::class)->findOneBy([
            'app' => is_null($key) ? Application::$configKey : $key,
            'key' => 'version'
        ]);
        if(is_null($dbVersion)){
            throw new WrongConfigurationException();
        }
        return $dbVersion;
    }

    /**
     * @throws \Throwable
     * @throws EntityManagerNotDefinedException
     */
    public function checkVersion(){
        if(is_null($this->entityManager)){
            throw new EntityManagerNotDefinedException();
        }
        Database::getInstance()->getEntityManager()->wrapInTransaction(function (){
            $v = $this->getDatabaseAppVersion();
            //TODO compare version in file and in database
            // if not equals = UPGRADE PROCESS
        });
    }

    public function getDefaultTimezone(): string
    {
        //TODO check config
        return \IntlTimeZone::createDefault()->toDateTimeZone()->getName();
    }

    public function setDefaultTimezone(): string
    {
        return \IntlTimeZone::createDefault()->toDateTimeZone()->getName();
    }

    public function getDefaultLocale(){
        return env('DEFAULT_LOCALE', 'en');
    }

    /**
     * Find another Applications and do some logic with them
     * @return void
     * @throws AutoloadMapNotFoundException
     */
    public function findApps(): void
    {
        $path = realpath('../apps');
        $apps = Finder::create()
            ->in($path)
            ->directories()
            ->depth(0);
        foreach ($apps as $file){
            $name = $file->getFilenameWithoutExtension();
            $path = $file->getPath();
            $this->applicationsList[] = compact('name','path');
            $this->registerAutoloadMap($file);
            $this->registerRoutes($file);
            $this->registerChildApplication($name);
        }
    }

    public function getApplicationsList(): ?array
    {
        return $this->applicationsList;
    }

    /**
     * Register application routes.php
     * @param SplFileInfo $file
     * @return void
     */
    public function registerRoutes(SplFileInfo $file): void
    {
        $path = Path::normalize($file->getRealPath() . DIRECTORY_SEPARATOR . 'inc/routes.php');
        $routes = require_once $path;
        $this->router->registerRoutes($routes);
        $this->dispatcher->dispatch($this->router, $this->router::E_ROUTES_ADDED);
    }

    public function registerChildApplication($app): void
    {
        require_once realpath('../apps/' . $app . '/index.php');
    }

    /**
     * @param SplFileInfo $file
     * @return void
     * @throws AutoloadMapNotFoundException
     */
    public function registerAutoloadMap(SplFileInfo $file): void
    {
        $path = Path::normalize($file->getRealPath() . DIRECTORY_SEPARATOR . 'vendor/autoload.php');
        if(file_exists($path)){
            require_once $path;
        } else {
            throw new AutoloadMapNotFoundException('Can not autoload class map on path ' . $path);
        }
    }

    /**
     * @return Finder
     */
    public static function getApplicationDirectories(): Finder
    {
        return Finder::create()->in('./apps/')->directories()->depth(0);
    }
}