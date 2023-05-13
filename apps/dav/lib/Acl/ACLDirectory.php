<?php

namespace Vorkfork\Apps\Dav\Acl;

use Sabre\DAV\Exception\NotFound;
use Sabre\DAV\FS\Directory;
use Sabre\DAVACL\ACLTrait;
use Sabre\DAVACL\IACL;

class ACLDirectory extends Directory implements IACL
{

	use ACLTrait;

	function __construct($path, $owner)
	{

		parent::__construct($path);
		$this->owner = $owner;

	}

	function getChild($name)
	{

		$path = $this->path . '/' . $name;

		if (!file_exists($path)) throw new NotFound('File with name ' . $path . ' could not be located');

		if (is_dir($path)) {

			return new ACLDirectory($path, $this->owner);

		} else {

			return new ACLFile($path, $this->owner);

		}
	}

	function getChildren()
	{

		$result = [];
		foreach (scandir($this->path) as $file) {

			if ($file === '.' || $file === '..') {
				continue;
			}
			$result[] = $this->getChild($file);

		}

		return $result;

	}

}