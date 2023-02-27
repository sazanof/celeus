<?php

use Vorkfork\Apps\Mail\Controllers\MailController;

if(!defined('INC_MODE')){
    exit;
}
return [
    '/mail'=>[
        'action'=> [MailController::class, 'index'],
        'methods'=> ['GET'],
        'defaults'=>[
            'public'=>true
        ]
    ],
];