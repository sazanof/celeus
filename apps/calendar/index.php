<?php

use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Vorkfork\Application\ApplicationUtilities;
use Vorkfork\Apps\Calendar\Application;
use Vorkfork\Core\Templates\TemplateRenderer;

if (!defined('INC_MODE')) {
	exit;
}
try {
	$app = new Application(
		utilities: ApplicationUtilities::getInstance()
	);
	$path = __DIR__ . '/templates';
	return TemplateRenderer::create($path)->loadTemplate('calendar', []);

} catch (MissingMappingDriverImplementation $e) {
	throw new MissingMappingDriverImplementation();
}