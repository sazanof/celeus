<?php

use Vorkfork\Apps\Mail\Controllers\MailController;
use Vorkfork\Core\Router\MainRouter;

if (!defined('INC_MODE')) {
	exit;
}
return MainRouter::group('/apps/mail/', [
	'accounts' => [
		'action' => [MailController::class, 'loadAccounts'],
		'methods' => ['GET'],
		'defaults' => [
			'auth' => true
		]
	],
	'accounts/add' => [
		'action' => [MailController::class, 'addAccount'],
		'methods' => ['GET', 'POST'],
		'defaults' => [
			'auth' => true
		]
	],
	'accounts/{id}' => [
		'action' => [MailController::class, 'saveAccount'],
		'methods' => ['PUT'],
		'defaults' => [
			'auth' => true
		]
	],
	'accounts/{id}/mailboxes' => [
		'action' => [MailController::class, 'syncMailboxes'],
		'methods' => ['GET'],
		'defaults' => [
			'auth' => true
		]
	],
]);
