<?php

namespace Vorkfork\Application;

class ApplicationInformation
{
	protected string $path;
	public array $information;

	public function __construct(string $appName)
	{
		$this->path = realpath('../apps/' . $appName . '/inc/' . $appName . '.json');
		if ($this->path && file_exists($this->path)) {
			$this->information = json_decode(file_get_contents($this->path), true);
		}
		return $this;
	}
}