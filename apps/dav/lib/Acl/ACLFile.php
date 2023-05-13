<?php

namespace Vorkfork\Apps\Dav\Acl;

use Sabre\DAV\FS\File;
use Sabre\DAVACL\ACLTrait;
use Sabre\DAVACL\IACL;

class ACLFile extends File implements IACL
{

	use ACLTrait;

	function __construct($path, $owner)
	{

		parent::__construct($path);
		$this->owner = $owner;

	}

}