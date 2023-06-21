<?php
declare(strict_types=1);

use Vorkfork\Core\Controllers\AppController;
use Vorkfork\Core\Controllers\InstallController;
use Vorkfork\Core\Controllers\LocaleController;
use Vorkfork\Core\Controllers\LoginController;
use Vorkfork\Core\Controllers\UserController;
use Vorkfork\Core\Translator\Translate;

if (!defined('INC_MODE')) {
	exit;
}
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
];