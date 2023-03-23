<?php

namespace Vorkfork\Core\Translator;

use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;

class Translate extends Translator
{
	protected static ?Translate $instance = null;
	protected string $locale;
	protected array $translations;
	protected string $cacheDir;
	protected string $localeDir;

	public function __construct()
	{
		$this->cacheDir = '../resources/cache';
		$this->localeDir = '../resources/locales/';
		$this->locale = Locale::getDefaultLocale();
		$this->translations = json_decode(file_get_contents(realpath($this->localeDir . $this->locale . '.json')), true);

		parent::__construct($this->locale, null, null);

		$this->addLoader('array', new ArrayLoader());
		$this->addResource('array', $this->translations, $this->locale);

		return $this;
	}

	/**
	 * @return Translate|null
	 */
	public static function getInstance(): ?Translate
	{
		self::$instance = !is_null(self::$instance) ? self::$instance : new self();
		return self::$instance;
	}

	public static function t(string $key, array $params = [], string $domain = null, string $locale = null)
	{
		self::getInstance();
		return self::$instance->trans($key, $params, $domain, $locale);
	}
}