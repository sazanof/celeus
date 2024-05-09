<?php
declare(strict_types=1);

use Vorkfork\Core\Controllers\AppController;
use Vorkfork\Core\Controllers\InstallController;
use Vorkfork\Core\Controllers\LocaleController;
use Vorkfork\Core\Controllers\LoginController;
use Vorkfork\Core\Controllers\UserController;
use Vorkfork\Core\Router\MainRouter;
use Vorkfork\Core\Translator\Translate;

if (!defined('INC_MODE')) {
	exit;
}
MainRouter::get(
	url: '',
	action: [AppController::class, 'index'],
	name: 'index',
	defaults: [
		'auth' => true
	],
);
MainRouter::prefix('apps', function () {
	MainRouter::prefix('{name}', function () {
		MainRouter::get(
			url: '',
			action: [AppController::class, 'runApp'],
			name: 'runApp',
			defaults: [
				'auth' => true
			],
		);
		MainRouter::get(
			url: 'img/{image}',
			action: [AppController::class, 'getApplicationPicture'],
			name: 'getApplicationPicture',
			defaults: [
				'auth' => true
			],
		);
	});
});
MainRouter::prefix('login', function () {
	MainRouter::get(
		url: '',
		action: [LoginController::class, 'getLogin'],
		name: 'logIn',
		defaults: [
			'title' => Translate::t('Log in to the system', [
				'{name}' => env('APP_NAME')
			])
		],
	);
	MainRouter::post(
		url: 'process',
		action: [LoginController::class, 'logIn'],
		name: 'logInProcess',
	);
	MainRouter::get(
		url: 'check',
		action: [LoginController::class, 'checkUserIsAuthenticated'],
		name: 'checkUserIsAuthenticated',
	);
});
MainRouter::prefix('locales', function () {
	MainRouter::get(
		url: '',
		action: [LocaleController::class, 'getLocaleList'],
		defaults: [
			'public' => true
		]
	);
	MainRouter::prefix('{lang}', function () {
		MainRouter::get(
			url: '',
			action: [LocaleController::class, 'getTranslation'],
			defaults: [
				'public' => true
			]
		);
		MainRouter::get(
			url: '{app}',
			action: [LocaleController::class, 'getApplicationTranslation'],
			defaults: [
				'auth' => true
			]
		);
	});
});
MainRouter::prefix('user', function () {
	MainRouter::get(
		url: '',
		action: [UserController::class, 'getUser'],
		defaults: [
			'auth' => true
		]
	);
	MainRouter::get(
		url: '{username}/avatar?size={size}',
		action: [UserController::class, 'getUserAvatar'],
		defaults: [
			'size' => 32,
			'auth' => true
		]
	);
});
MainRouter::prefix('install', function () {
	MainRouter::get(
		url: '{step}',
		action: [InstallController::class, 'install'],
		defaults: [
			'step' => 0,
			'public' => true
		]
	);
	MainRouter::post(
		url: '{step}',
		action: [InstallController::class, 'install'],
		defaults: [
			'step' => 0,
			'public' => true
		]
	);
});
MainRouter::get(
	url: '/logout',
	action: [LoginController::class, 'logOut'],
	defaults: [
		'auth' => true
	]
);/*
return [
	'/' => [
		'action' => [AppController::class, 'index'],
		'methods' => ['GET'],
		'defaults' => [
			'auth' => true
		]
	],
	'/apps/{name}' => [
		'action' => [AppController::class, 'runApp'],
		'methods' => ['GET', 'POST'],
		'defaults' => [
			'auth' => true
		]
	],
	'/apps/{name}/img/{image}' => [
		'action' => [AppController::class, 'getApplicationPicture'],
		'methods' => ['GET'],
		'defaults' => [
			'auth' => true
		]
	],
	'/login' => [
		'action' => [LoginController::class, 'getLogin'],
		'methods' => ['GET'],
		'defaults' => [
			'title' => Translate::t('Log in to the system', [
				'{name}' => env('APP_NAME')
			])
		]
	],
	'/login/process' => [
		'action' => [LoginController::class, 'logIn'],
		'methods' => ['POST'],
	],
		'/logout' => [
			'action' => [LoginController::class, 'logOut'],
			'methods' => ['GET'],
			'defaults' => [
				'auth' => true
			]
		],
	'/login/check' => [
		'action' => [LoginController::class, 'checkUserIsAuthenticated'],
		'methods' => ['GET'],
	],
	'/locales' => [
		'action' => [LocaleController::class, 'getLocaleList'],
		'methods' => ['GET'],
		'defaults' => [
			'public' => true
		]
	],
	'/locales/{lang}' => [
		'action' => [LocaleController::class, 'getTranslation'],
		'methods' => ['GET'],
		'defaults' => [
			'public' => true
		]
	],
	'/locales/{lang}/{app}' => [
		'action' => [LocaleController::class, 'getApplicationTranslation'],
		'methods' => ['GET'],
		'defaults' => [
			'auth' => true
		]
	],
	'/install/{step}' => [
		'action' => [InstallController::class, 'install'],
		'methods' => ['GET', 'POST'],
		'defaults' => [
			'step' => 0,
			'public' => true
		]
	],
	'/user' => [
		'action' => [UserController::class, 'getUser'],
		'methods' => ['GET'],
		'defaults' => [
			'auth' => true
		]
	],
	'/user/{username}/avatar?size={size}' => [
		'action' => [UserController::class, 'getUserAvatar'],
		'methods' => ['GET'],
		'defaults' => [
			'size' => 32,
			'auth' => true
		]
	],
];*/
