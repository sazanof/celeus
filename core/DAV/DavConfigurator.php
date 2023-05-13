<?php

namespace Vorkfork\Core\DAV;

use Vorkfork\Core\Config\Config;

class DavConfigurator extends Config
{
	/**
	 * @var string|null
	 */
	protected ?string $fileName = 'dav';
	/**
	 * @var string|mixed|null
	 */
	protected ?string $rootPath = null;
	/**
	 * @var string|mixed|null
	 */
	protected ?string $sharesPath = null;
	/**
	 * @var string|mixed|null
	 */
	protected ?string $caldavPath = null;
	/**
	 * @var string|mixed|null
	 */
	protected ?string $carddavPath = null;
	/**
	 * @var string|null
	 */
	protected ?string $baseUri = null;
	/**
	 * @var string|null
	 */
	protected ?string $locks = null;

	protected ?string $principals = null;

	/**
	 * @var DavConfigurator|null
	 */
	protected static DavConfigurator|null $instance = null;

	/**
	 * @param $fileName
	 * @throws \Vorkfork\Core\Exceptions\ConfigurationNotFoundException
	 */
	public function __construct($fileName = null)
	{
		parent::__construct($fileName);
		$this->rootPath = $this->getConfigValue('base');
		$this->sharesPath = $this->getConfigValue('shares');
		$this->caldavPath = $this->getConfigValue('caldav');
		$this->carddavPath = $this->getConfigValue('carddav');
		$this->baseUri = $this->getConfigValue('baseUri');
		$this->locks = $this->getConfigValue('locks');
		$this->principals = $this->getConfigValue('principals');
		self::$instance = $this;
	}

	public static function getInstance(): ?DavConfigurator
	{
		if (is_null(self::$instance)) {
			return new self();
		}
		return self::$instance;
	}

	/**
	 * @return mixed|string|null
	 */
	public function getRootPath()
	{
		return $this->rootPath;
	}

	/**
	 * @return mixed|string|null
	 */
	public function getSharesPath()
	{
		return $this->sharesPath;
	}

	/**
	 * @return mixed|string|null
	 */
	public function getCalDAVPath()
	{
		return $this->caldavPath;
	}

	/**
	 * @return mixed|string|null
	 */
	public function getCardDAVPath()
	{
		return $this->carddavPath;
	}

	/**
	 * @return string|null
	 */
	public function getBaseUri(): ?string
	{
		return $this->baseUri;
	}

	/**
	 * @return string|null
	 */
	public function getLocks(): ?string
	{
		return $this->locks;
	}

	/**
	 * @param string|null $principals
	 */
	public function getPrincipals(): string
	{
		return $this->principals;
	}
}