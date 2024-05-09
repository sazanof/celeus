<?php

declare(strict_types=1);

namespace Vorkfork\Core\Router;

use Vorkfork\Core\Controllers\Controller;
use Vorkfork\Router\IRouter;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class MainRouter implements IRouter
{
	public const E_ROUTES_ADDED = 'on.routes.added';
	protected string $path;
	/**
	 * @var RouteCollection
	 */
	protected RouteCollection $routes;
	/**
	 * @var Controller | null
	 */
	protected ?Controller $executor = null;

	protected static ?MainRouter $instance = null;

	protected EventDispatcher $dispatcher;

	protected UrlMatcher $matcher;

	protected string $prefix = '';

	public function __construct()
	{
		$this->routes = new RouteCollection();
		self::$instance = $this;
	}

	public function setDispatcher(EventDispatcher $dispatcher)
	{
		$this->dispatcher = $dispatcher;
		$this->dispatcher->addListener(self::E_ROUTES_ADDED, function ($router) {
			//dump('on.routes.php.added', $router);
		});
	}

	public static function getInstance()
	{
		return is_null(self::$instance) ? new self() : self::$instance;
	}

	public function registerRoutes($routes): IRouter
	{
		foreach ($routes as $url => $route) {
			//TODO установка роута для install и upgrade, если приложение не установлено
			$_route = $this->setRoute($url, $route['methods'], $route['action']);

			if (isset($route['defaults'])) {
				$_route->addDefaults($route['defaults']);
			}
			if (isset($route['requirements'])) {
				$_route->addRequirements($route['requirements']);
			}
		}
		return $this;
	}

	public function addRoutesFromAppInc(string $path = null)
	{
		$path = is_null($path) ? realpath('../inc/routes.php') : $path;
		require_once $path;
	}

	public function setRoute(string $url, array $methods, array $action)
	{
		$route = new Route($url, [
			'_controller' => $action,
			'_method' => $methods,
		]);
		$route->setMethods($methods);
		$this->routes->add($url, $route);
		return $route;
	}

	public function getRoutes()
	{
		return $this->routes;
	}

	/**
	 * @param $url
	 * @return array
	 */
	public function matchRoute($url): array
	{
		$context = new RequestContext();
		$context->fromRequest(Request::createFromGlobals());
		$matcher = new UrlMatcher($this->getRoutes(), $context);
		return $matcher->match($url);
	}

	/**
	 * @param string $url
	 * @return RedirectResponse
	 */
	public function redirectTo(string $url): RedirectResponse
	{
		$redirect = new RedirectResponse($url);
		return $redirect->send();
	}

	public static function group(string $prefix, array $routes): array
	{
		$ar = [];
		foreach ($routes as $key => $routeArray) {
			$ar[$prefix . $key] = $routeArray;
		}
		return $ar;
	}

	public function getRoute(string $url)
	{
		// TODO: Implement getRoute() method.
	}

	public static function app(string $name, \Closure $closure)
	{
		self::$instance->prefix = '/apps/' . $name . '/';
		if (is_callable($closure)) {
			$closure(self::$instance, $name);
		}
		self::$instance->prefix = '';
	}

	public static function prefix(string $prefix, \Closure $closure)
	{
		$part = trim($prefix, '/\\') . '/';
		self::$instance->prefix .= $part;
		if (is_callable($closure)) {
			$closure($prefix);
		}
		self::$instance->prefix = rtrim(self::$instance->prefix, $part) . '/';
	}

	public static function add(
		string       $url,
		array        $action,
		string|array $method = 'GET',
		string       $name = null,
					 $defaults = [],
					 $requirements = []
	)
	{
		$name = is_null($name) ? uniqid('route_', true) : $name;
		if (!empty(self::$instance->prefix)) {
			$url = trim(self::$instance->prefix, '/\\') . ($url === '' ? '' : '/') . $url;
			//dump(trim(self::$instance->prefix, '/\\'), $url);
		}
		$route = new Route(
			$url,
			[
				'_controller' => $action,
			]
		);
		$route->setMethods($method);
		$route->addDefaults($defaults);
		$route->addRequirements($requirements);
		self::$instance->routes->add($name, $route);
	}

	public static function get(string $url, array $action, string $name = null, $defaults = [], $requirements = [])
	{
		self::add(
			url: $url,
			action: $action,
			name: $name,
			defaults: $defaults,
			requirements: $requirements
		);
	}

	public static function post(string $url, array $action, string $name = null, $defaults = [], $requirements = [])
	{
		self::add(
			url: $url,
			action: $action,
			method: 'POST',
			name: $name,
			defaults: $defaults,
			requirements: $requirements
		);
	}

	public static function put(string $url, array $action, string $name = null, $defaults = [], $requirements = [])
	{
		self::add(
			url: $url,
			action: $action,
			method: 'PUT',
			name: $name,
			defaults: $defaults,
			requirements: $requirements
		);
	}

	public static function delete(string $url, array $action, string $name = null, $defaults = [], $requirements = [])
	{
		self::add(
			url: $url,
			action: $action,
			method: 'delete',
			name: $name,
			defaults: $defaults,
			requirements: $requirements
		);
	}

	public static function options(string $url, array $action, string $name = null, $defaults = [], $requirements = [])
	{
		self::add(
			url: $url,
			action: $action,
			method: 'OPTIONS',
			name: $name,
			defaults: $defaults,
			requirements: $requirements
		);
	}
}
