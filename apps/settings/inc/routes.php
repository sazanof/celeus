<?php

use Vorkfork\Apps\Settings\Controllers\SettingsController;
use Vorkfork\Core\Router\MainRouter;

if (!defined('INC_MODE')) {
	exit;
}
return MainRouter::group('/app/settings/', [
	'profile' => [
		'action' => [SettingsController::class, 'saveProfile'],
		'methods' => ['POST'],
		'defaults' => [
			'auth' => true
		]
	]
]);