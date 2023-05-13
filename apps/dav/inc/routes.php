<?php

use Vorkfork\Apps\Dav\Controllers\DavController;
use Vorkfork\Core\Router\MainRouter;

if (!defined('INC_MODE')) {
	exit;
}
return MainRouter::group('app/dav/', [
	'server/' => [
		'action' => [DavController::class, 'server'],
		'methods' => ['GET'],
	],
	'server/{uri}' => [
		'action' => [DavController::class, 'server'],
		'methods' => ['POST', 'GET', 'OPTIONS', 'PROPFIND', 'MKCOL', 'DELETE'],
		'requirements' => [
			'uri' => '.+'
		],
		'defaults' => [
			'uri' => ''
		]
	],
]);