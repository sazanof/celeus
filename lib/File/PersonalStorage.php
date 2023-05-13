<?php

namespace Vorkfork\File;

use Symfony\Component\Filesystem\Path;
use Vorkfork\Auth\Auth;
use Vorkfork\DTO\UserDto;

class PersonalStorage extends Storage
{
	protected UserDto $user;

	/**
	 * @throws \Exception
	 */
	public function __construct()
	{
		if (!Auth::isAuthenticated()) {
			throw new \Exception('Auth required');
		}
		$this->user = Auth::user();
		parent::__construct();
	}

	public function addBasePath()
	{
		return Path::normalize($this->config->getConfigValue('base') . DIRECTORY_SEPARATOR . $this->user->username);
	}
}