<?php

use Vorkfork\Apps\Dav\Controllers\DavController;
use Vorkfork\Core\Router\MainRouter;

if (!defined('INC_MODE')) {
	exit;
}
MainRouter::app('dav', function (MainRouter $router) {
	MainRouter::prefix('server', function () {
		MainRouter::get(
			url: '',
			action: [DavController::class, 'server']
		);
		MainRouter::add(
			url: '',
			action: [DavController::class, 'server'],
			method: ['POST', 'GET', 'OPTIONS', 'PROPFIND', 'MKCOL', 'DELETE'],
			defaults: [
				'uri' => ''
			],
			requirements: [
				'uri' => '.+'
			]
		);
	});

});
/*return MainRouter::group('apps/dav/', [
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
]);*/
