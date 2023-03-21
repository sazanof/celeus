<?php

use Vorkfork\Apps\Mail\Controllers\CalendarController;

if (!defined('INC_MODE')) {
    exit;
}
return [
    '/mail' => [
        'action' => [CalendarController::class, 'index'],
        'methods' => ['GET'],
        'defaults' => [
            'public' => true
        ]
    ],
];