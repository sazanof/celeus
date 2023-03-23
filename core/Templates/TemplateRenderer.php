<?php

namespace Vorkfork\Core\Templates;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Vorkfork\Core\Translator\Translate;
use Vorkfork\Template\ITemplateRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TemplateRenderer implements ITemplateRenderer
{
	protected array $paths = [];
	private string $defaultPath;
	private Environment $template;

	public function __construct($defaultPath = '../resources/templates')
	{
		$this->defaultPath = $defaultPath;
		$this->paths[] = $this->defaultPath;
	}

	/**
	 * @param $path
	 * @return TemplateRenderer
	 */
	public static function create($path): TemplateRenderer
	{
		return new self($path);
	}

	public function setPaths($paths = [])
	{
		$this->paths = array_merge($paths, $this->paths);
	}

	/**
	 * @throws \Twig\Error\SyntaxError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\LoaderError
	 */
	public function loadTemplate(string $name, $data = []): string
	{
		$loader = new FilesystemLoader($this->paths);
		$this->template = new Environment($loader);
		$this->template->addFunction(new TwigFunction('env', function ($param, $default) {
			return env($param, $default);
		}));
		$this->template->addFunction(new TwigFunction('t', function (string $key, array $params = [], string $domain = null, string $locale = null) {
			return Translate::t($key);
		}));
		$nameWithExtension = "{$name}.twig";
		return $this->template->render($nameWithExtension, $data);
	}
}
