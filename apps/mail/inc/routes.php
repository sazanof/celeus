<?php

use Vorkfork\Apps\Mail\Controllers\MailController;

if (!defined('INC_MODE')) {
	exit;
}
return [
	'/app/mail/accounts' => [
		'action' => [MailController::class, 'loadAccounts'],
		'methods' => ['GET'],
		'defaults' => [
			'auth' => true
		]
	],
];