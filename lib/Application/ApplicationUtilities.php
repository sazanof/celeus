<?php

namespace Vorkfork\Application;

use Vorkfork\Core\Application;
use Vorkfork\Core\Exceptions\AutoloadMapNotFoundException;
use Vorkfork\Core\Exceptions\EntityManagerNotDefinedException;
use Vorkfork\Core\Exceptions\ErrorResponse;
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
use Vorkfork\File\File;

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

	public function __construct()
	{
		$Vorkfork_Version = '';
		$Vorkfork_VersionArray = [];
		if (php_sapi_name() === "cli") {
			require realpath('./inc/version.php');
		} else {
			require realpath('../inc/version.php');
		}
		$this->version = $Vorkfork_Version;
		$this->versionArray = $Vorkfork_VersionArray;

		$this->database = Database::getInstance();

		self::$instance = $this;
	}

	public function setDispatcher(EventDispatcher $dispatcher)
	{
		$this->dispatcher = $dispatcher;
	}

	public function getDispatcher()
	{
		return $this->dispatcher;
	}

	public function setRouter(MainRouter $router)
	{
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
		return is_null($this->entityManager) ? Database::getInstance()->getEntityManager() : $this->entityManager;
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
		if (is_null($dbVersion)) {
			throw new WrongConfigurationException();
		}
		return $dbVersion;
	}

	/**
	 * @throws \Throwable
	 * @throws EntityManagerNotDefinedException
	 */
	public function checkVersion()
	{
		if (is_null($this->entityManager)) {
			throw new EntityManagerNotDefinedException();
		}
		Database::getInstance()->getEntityManager()->wrapInTransaction(function () {
			$v = $this->getDatabaseAppVersion();
			//TODO compare version in file and in database
			// if not equals = UPGRADE PROCESS
		});
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
		foreach ($apps as $file) {
			$name = $file->getFilenameWithoutExtension();
			$path = $file->getPath();
			$this->applicationsList[] = compact('name', 'path');
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
		if (file_exists($path)) {
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

	public static function getApplicationInformation($appName)
	{

		return new ApplicationInformation($appName);
	}

	/**
	 * Gets build js in application
	 * @param string $app
	 * @param string $folder
	 * @return array|null
	 */
	public static function buildApplicationJs(string $app, string $folder): ?array
	{
		$pathRelative = '../apps/' . $app . DIRECTORY_SEPARATOR . $folder;
		$path = Path::normalize(realpath($pathRelative));
		if (file_exists($path)) {
			$scriptsFiles = Finder::create()
				->in($path)
				->path($app . '.js')
				->files();
			if ($scriptsFiles->count() > 0) {
				$scripts = [];
				foreach ($scriptsFiles as $file) {
					$scripts[] = $file->getFilename();
				}
				return $scripts;
			}
		}
		return null;
	}

	/**
	 * @param mixed $data
	 * @param int $code
	 * @return ErrorResponse
	 */
	public static function errorResponse(string $className, int $code = 500)
	{
		return (new ErrorResponse($className, $code));
	}
}