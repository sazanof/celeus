<?php

namespace Vorkfork\Core\Config;

use Vorkfork\Config\IConfig;
use Vorkfork\Core\Exceptions\ConfigurationNotFoundException;
use Vorkfork\File\File;

class Config implements IConfig {
	private mixed $configArray = [];
	protected ?string $fileName = null;

	/**
	 * @param $fileName
	 * @throws ConfigurationNotFoundException
	 */
	public function __construct($fileName = null) {
		$this->fileName = $this->fileName !== null ? $this->fileName : $fileName;
		if(!is_null($this->fileName)){
			if(php_sapi_name() === "cli"){
				$pathToConfig = realpath("./config/{$this->fileName}.php");
			} else{
				$pathToConfig = realpath("../config/{$this->fileName}.php");
			}

			$fs = new File($pathToConfig);
			if($fs->exists($pathToConfig)){
				$this->configArray = require($pathToConfig);
			} else{
				throw new ConfigurationNotFoundException();
			}
		}
		return $this;
	}

	public function getConfig(): array {
		return $this->configArray;
	}

	public function getConfigValue($key): mixed {
		return $this->configArray[$key];
	}

	public static function fromArray($configArray): Config {
		$c = (new self(null));
		$c->configArray = $configArray;
		return $c;
	}

	/**
	 * Helper for get method
	 * @param $array
	 * @param $key
	 * @return mixed
	 */
	private static function getValueFromArray($array, $key) {
		return $array[$key];
	}

	/**
	 * Get current config value
	 * Example Config::get('app.pages.first') => config/app.php -> return [ 'pages' => ['first' => 1, 'second' => 2]]
	 */
	public static function get($key) {
		$explodedKey = explode('.', $key);
		$file = $explodedKey[0];
		unset($explodedKey[0]);
		if(!empty($explodedKey)){
			try {
				$cnf = new self($file);
				$currentValue = $cnf->configArray;
				foreach($explodedKey as $_key) {
					$currentValue = $cnf::getValueFromArray($currentValue, $_key);
				}
				return $currentValue;
			} catch(ConfigurationNotFoundException $e) {
				// log here
			}

		} else{
			return '';
		}
	}
}
