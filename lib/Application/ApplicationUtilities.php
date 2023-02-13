<?php

namespace Celeus\Application;

use Celeus\Core\Application;
use Celeus\Core\Exceptions\AutoloadMapNotFoundException;
use Celeus\Core\Exceptions\EntityManagerNotDefinedException;
use Celeus\Core\Exceptions\WrongConfigurationException;
use Celeus\Core\Models\Config;
use Celeus\Core\Router\MainRouter;
use Celeus\Database\Database;
use Celeus\File\File;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ApplicationUtilities
{
    private string $version;
    private array $versionArray;
    private ?EntityManager $entityManager = null;
    protected static ApplicationUtilities $instance;
    protected Database $database;
    protected EventDispatcher $dispatcher;
    protected MainRouter $router;

    public function __construct(){
        $Celeus_Version = '';
        $Celeus_VersionArray = [];
        require realpath('../inc/version.php');
        $this->version = $Celeus_Version;
        $this->versionArray = $Celeus_VersionArray;
        $this->database = Database::getInstance();
        self::$instance = $this;
    }

    public function setDispatcher(EventDispatcher $dispatcher){
        $this->dispatcher = $dispatcher;
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

    public function getEntityManager(): ?EntityManager
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
     */
    public function findApps(): void
    {
        $path = realpath('../apps');
        $apps = Finder::create()
            ->in($path)
            ->directories()
            ->depth(0);
        foreach ($apps as $file){
            $this->registerAutoloadMap($file);
            $this->registerRoutes($file);
        }
    }

    /**
     * Register application routes.php
     * @param SplFileInfo $file
     * @return void
     */
    public function registerRoutes(SplFileInfo $file){
        $path = Path::normalize($file->getRealPath() . DIRECTORY_SEPARATOR . 'inc/routes.php');
        $routes = require_once $path;
        $this->router->registerRoutes($routes);
        $this->dispatcher->dispatch($this->router, $this->router::E_ROUTES_ADDED);
    }

    /**
     * @param SplFileInfo $file
     * @return void
     * @throws AutoloadMapNotFoundException
     */
    public function registerAutoloadMap(SplFileInfo $file){
        $path = Path::normalize($file->getRealPath() . DIRECTORY_SEPARATOR . 'vendor/autoload.php');
        if(file_exists($path)){
            require_once $path;
        } else {
            throw new AutoloadMapNotFoundException('Can not autoload class map on path ' . $path);
        }
    }
}