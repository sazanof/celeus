<?php
declare(strict_types=1);

use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Vorkfork\Application\ApplicationUtilities;
use Vorkfork\Apps\Mail\Application;
use Vorkfork\Core\Events\AddApplicationScripts;
use Vorkfork\Core\Templates\TemplateRenderer;

if (!defined('INC_MODE')) {
	header('Location: /');
	exit;
}
try {
	$u = ApplicationUtilities::getInstance();
	$scripts = $u::buildApplicationJs('mail', 'resources/js');
	// инициализируем отправку события, чтобы подключить скрипты приложения в нужное место
	$this->dispatcher->dispatch(new AddApplicationScripts($scripts), AddApplicationScripts::NAME);
	$app = new Application(
		utilities: ApplicationUtilities::getInstance()
	);
	$path = __DIR__ . '/templates';
	return TemplateRenderer::create($path)->loadTemplate('mail', []);

} catch (MissingMappingDriverImplementation $e) {
	throw new MissingMappingDriverImplementation();
}