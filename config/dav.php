<?php

use Symfony\Component\Filesystem\Path;

return [
	'base' => Path::normalize(env('DATA_DIRECTORY')),
	'baseUri' => '/apps/dav/server',
	'locks' => 'data/locks',
	'shares' => env('DATA_DIRECTORY') . DIRECTORY_SEPARATOR . 'shares',
	'caldav' => env('DATA_DIRECTORY') . DIRECTORY_SEPARATOR . 'caldav',
	'carddav' => env('DATA_DIRECTORY') . DIRECTORY_SEPARATOR . 'carddav',
	'principals' => 'principals'
];