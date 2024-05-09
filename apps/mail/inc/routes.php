<?php

use Vorkfork\Apps\Mail\Controllers\MailController;
use Vorkfork\Core\Router\MainRouter;

if (!defined('INC_MODE')) {
	exit;
}

MainRouter::app('mail', function (MainRouter $router) {
	MainRouter::prefix('accounts', function () {
		MainRouter::get(
			url: '',
			action: [MailController::class, 'loadAccounts'],
			defaults: [
				'auth' => true
			]
		);
		MainRouter::get(
			url: 'add',
			action: [MailController::class, 'loadAccounts'],
			defaults: [
				'auth' => true
			]
		);
		MainRouter::post(
			url: 'add',
			action: [MailController::class, 'loadAccounts'],
			defaults: [
				'auth' => true
			]
		);
		MainRouter::prefix('{id}', function () {
			MainRouter::put(
				url: '',
				action: [MailController::class, 'saveAccount'],
				defaults: [
					'auth' => true
				]
			);
			MainRouter::get(
				url: 'mailboxes',
				action: [MailController::class, 'syncMailboxes'],
				defaults: [
					'auth' => true
				]
			);
		});
	});
	MainRouter::prefix('mailboxes', function () {

		MainRouter::prefix('{id}', function () {
			MainRouter::get(
				url: 'sync',
				action: [MailController::class, 'syncMailbox'],
				defaults: [
					'auth' => true
				]
			);
			MainRouter::post(
				url: 'sync',
				action: [MailController::class, 'syncMailbox'],
				defaults: [
					'auth' => true
				]
			);
			MainRouter::post(
				url: 'messages',
				action: [MailController::class, 'getMessages'],
				defaults: [
					'auth' => true
				]
			);
			MainRouter::get(
				url: 'messages',
				action: [MailController::class, 'getMessages'],
				defaults: [
					'auth' => true
				]
			);
		});
	});
});
