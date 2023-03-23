<?php

use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Vorkfork\Application\ApplicationUtilities;
use Vorkfork\Apps\Settings\Application;
use Vorkfork\Core\Templates\TemplateRenderer;

if (!defined('INC_MODE')) {
	exit;
}
try {
	$app = new Application(
		utilities: ApplicationUtilities::getInstance()
	);
	$path = __DIR__ . '/resources/templates';
	return TemplateRenderer::create($path)->loadTemplate('settings', []);

} catch (MissingMappingDriverImplementation $e) {
	throw new MissingMappingDriverImplementation();
}