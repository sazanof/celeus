<?php

declare(strict_types=1);

namespace Vorkfork\Apps\Dav\Collections;

use Sabre\DAVACL\FS\HomeCollection;

class SharesCollection extends HomeCollection
{
	/**
	 * @var string
	 */
	public $collectionName = 'shares';
}