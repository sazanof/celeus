<?php

use Vorkfork\Apps\Settings\Controllers\SettingsController;
use Vorkfork\Core\Router\MainRouter;

if (!defined('INC_MODE')) {
	exit;
}
return MainRouter::group('/apps/settings/', [
	'profile' => [
		'action' => [SettingsController::class, 'saveProfile'],
		'methods' => ['POST'],
		'defaults' => [
			'auth' => true
		]
	],
	'profile/photo' => [
		'action' => [SettingsController::class, 'saveProfilePhoto'],
		'methods' => ['POST'],
		'defaults' => [
			'auth' => true
		]
	]
]);