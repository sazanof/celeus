<?php
declare(strict_types=1);

use Vorkfork\Core\Controllers\AppController;
use Vorkfork\Core\Controllers\InstallController;
use Vorkfork\Core\Controllers\LocaleController;
use Vorkfork\Core\Controllers\LoginController;
use Vorkfork\Core\Controllers\UserController;

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
    '/user' => [
        'action' => [UserController::class, 'getUser'],
        'methods' => ['GET'],
        'defaults' => [
            'auth' => true
        ]
    ],
    '/login' => [
        'action' => [LoginController::class, 'getLogin'],
        'methods' => ['GET'],
        'defaults' => [
            'title' => 'Default title'
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
    '/install/{step}' => [
        'action' => [InstallController::class, 'install'],
        'methods' => ['GET', 'POST'],
        'defaults' => [
            'step' => 0,
            'public' => true
        ]
    ],
];