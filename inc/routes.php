<?php
if(!defined('INC_MODE')){
    exit;
}
return [
    '/'=>[
        'action'=> [Celeus\Core\Controllers\AppController::class, 'index'],
        'methods'=> ['GET']
    ],
    '/login/{id}'=>[
        'action'=> [Celeus\Core\Controllers\LoginController::class, 'processLogin'],
        'methods'=> ['POST']
    ],
    '/login'=>[
        'action'=> [Celeus\Core\Controllers\LoginController::class, 'getLogin'],
        'methods'=> ['GET']
    ],
    '/locales' => [
        'action'=> [Celeus\Core\Controllers\LocaleController::class, 'getLocaleList'],
        'methods'=> ['GET'],
        'defaults'=>[
            'public'=>true
        ]
    ],
    '/locales/{lang}' => [
        'action'=> [Celeus\Core\Controllers\LocaleController::class, 'getTranslation'],
        'methods'=> ['GET'],
        'defaults'=>[
            'public'=>true
        ]
    ],
    '/install/{step}' => [
        'action'=> [Celeus\Core\Controllers\InstallController::class, 'install'],
        'methods'=> ['GET', 'POST'],
        'defaults'=>[
            'step'=>0,
            'public'=>true
        ]
    ],
];