<?php

declare(strict_types=1);

namespace Vorkfork\Router;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

interface IRouter
{
    public function registerRoutes($routes): IRouter;

    public function setRoute(string $url, array $methods, array $action);

    public function matchRoute(string $url);

    public function getRoute(string $url);

    public function redirectTo(string $url) : RedirectResponse;

    public function setDispatcher(EventDispatcher $dispatcher);
}
