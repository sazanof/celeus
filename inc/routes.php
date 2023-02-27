<?php
if(!defined('INC_MODE')){
    exit;
}
return [
    '/'=>[
        'action'=> [Vorkfork\Core\Controllers\AppController::class, 'index'],
        'methods'=> ['GET']
    ],
    '/login/{id}'=>[
        'action'=> [Vorkfork\Core\Controllers\LoginController::class, 'processLogin'],
        'methods'=> ['POST']
    ],
    '/login'=>[
        'action'=> [Vorkfork\Core\Controllers\LoginController::class, 'getLogin'],
        'methods'=> ['GET']
    ],
    '/locales' => [
        'action'=> [Vorkfork\Core\Controllers\LocaleController::class, 'getLocaleList'],
        'methods'=> ['GET'],
        'defaults'=>[
            'public'=>true
        ]
    ],
    '/locales/{lang}' => [
        'action'=> [Vorkfork\Core\Controllers\LocaleController::class, 'getTranslation'],
        'methods'=> ['GET'],
        'defaults'=>[
            'public'=>true
        ]
    ],
    '/install/{step}' => [
        'action'=> [Vorkfork\Core\Controllers\InstallController::class, 'install'],
        'methods'=> ['GET', 'POST'],
        'defaults'=>[
            'step'=>0,
            'public'=>true
        ]
    ],
];