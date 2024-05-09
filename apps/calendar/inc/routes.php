<?php

use Vorkfork\Apps\Calendar\Controllers\CalendarController;
use Vorkfork\Core\Router\MainRouter;

if (!defined('INC_MODE')) {
	exit;
}
MainRouter::app('calendar', function () {
	MainRouter::get(
		url: 'test',
		action: [CalendarController::class, 'index'],
		defaults: [
			'auth' => true
		]
	);
});
