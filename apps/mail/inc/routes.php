<?php

use Vorkfork\Apps\Mail\Controllers\MailController;

if (!defined('INC_MODE')) {
	exit;
}
return [
	'/apps/mail/accounts' => [
		'action' => [MailController::class, 'loadAccounts'],
		'methods' => ['GET'],
		'defaults' => [
			'auth' => true
		]
	],
];