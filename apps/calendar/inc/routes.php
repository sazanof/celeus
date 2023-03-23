<?php

use Vorkfork\Apps\Calendar\Controllers\CalendarController;

if (!defined('INC_MODE')) {
	exit;
}
return [
	'/apps/calendar/test' => [
		'action' => [CalendarController::class, 'index'],
		'methods' => ['GET'],
		'defaults' => [
			'auth' => true
		]
	],
];