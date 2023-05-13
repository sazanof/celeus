<?php
declare(strict_types=1);

use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Vorkfork\Application\ApplicationUtilities;
use Vorkfork\Application\Session;
use Vorkfork\Apps\Settings\Application;
use Vorkfork\Auth\Auth;
use Vorkfork\Core\Events\AddApplicationScripts;
use Vorkfork\Core\Templates\TemplateRenderer;

Session::start();

if (!defined('INC_MODE')) {
	exit;
}
try {
	$u = ApplicationUtilities::getInstance();
	$scripts = $u::buildApplicationJs('settings', 'resources/js');
	// инициализируем отправку события, чтобы подключить скрипты приложения в нужное место
	$this->dispatcher->dispatch(new AddApplicationScripts($scripts), AddApplicationScripts::NAME);

	$app = new Application(
		utilities: $u
	);
	$path = __DIR__ . '/resources/templates';
	return TemplateRenderer::create($path)->loadTemplate('settings', [
		'user' => Auth::user()->toString(true)
	]);

} catch (MissingMappingDriverImplementation $e) {
	throw new MissingMappingDriverImplementation();
}