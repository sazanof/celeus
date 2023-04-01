<?php

use Vorkfork\Apps\Settings\Controllers\SettingsController;

if (!defined('INC_MODE')) {
	exit;
}
return [
	'/apps/settings/user' => [
		'action' => [SettingsController::class, 'index'],
		'methods' => ['GET'],
		'defaults' => [
			'auth' => true
		]
	],

];