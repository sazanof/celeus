<?php
require_once 'tools/php-cs-fixer/vendor/autoload.php';
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('node_modules')
    ->exclude('tools')
    ->exclude('vendor');

$config = new PhpCsFixer\Config();
return $config->setFinder($finder);