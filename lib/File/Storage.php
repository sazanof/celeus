<?php

namespace Vorkfork\File;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Vorkfork\Core\Config\Config;
use Vorkfork\Core\DAV\DavConfigurator;
use Vorkfork\Security\Str;

/**
 * @method static bool existsStatic(string $path) Check if file or directory exists in Storage
 * @method static bool mkdirStatic(string $path) Check if file or directory exists in Storage
 * @method static bool moveStatic(string $directory, string $filename) Check if file or directory exists in Storage
 * @method static bool deleteStatic(string $path) Check if file or directory exists in Storage
 */
class Storage
{
	protected Filesystem $filesystem;
	protected Config $config;
	protected File $file;
	protected static ?Storage $instance = null;
	protected string $basePath;

	public function __construct()
	{
		$this->filesystem = new Filesystem();
		$this->config = DavConfigurator::getInstance();
		$this->basePath = $this->addBasePath();
		if (!$this->exists($this->basePath)) {
			$this->filesystem->mkdir($this->basePath);
		}
		self::$instance = $this;
	}

	public static function getInstance(): ?Storage
	{
		if (is_null(self::$instance)) {
			return new static();
		}
		return self::$instance;
	}

	public static function __callStatic(string $method, array $arguments)
	{
		self::getInstance();
		$method = Str::trimEnd($method, 'Static');
		if (!is_callable([self::$instance, $method])) {
			throw new \Exception('Method not found');
		}
		return self::$instance->$method(...$arguments);
		//dd($_method);
	}

	public function addBasePath()
	{
		return $this->config->getConfigValue('base');
	}

	public function file(File $file): File
	{
		$this->file = $file;
		return $this->file;
	}

	/**
	 * @param iterable|string|null $path
	 * @return bool
	 */
	public function exists(iterable|string $path = null): bool
	{
		$path = Str::trimStart($path, $this->basePath);
		return $this->filesystem->exists($path);
	}

	public function move(string $directory, string $filename)
	{

		$this->file->move($this->basePath . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . $filename);
		return $this->file;
	}

	public function put(string $directory, string $filename)
	{

		$this->file->move($this->basePath . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . $filename);
		return $this->file;
	}

	public static function delete()
	{

	}
}