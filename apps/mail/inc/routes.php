<?php

use Vorkfork\Apps\Mail\Controllers\SettingsController;

if (!defined('INC_MODE')) {
	exit;
}
return [
	'/mail' => [
		'action' => [SettingsController::class, 'index'],
		'methods' => ['GET'],
		'defaults' => [
			'public' => true
		]
	],
];