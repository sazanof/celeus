<?php

namespace Vorkfork\Core\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Vorkfork\Auth\Auth;

class LoginController extends Controller
{
    protected bool $useTemplateRenderer = true;

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getLogin(): string
    {
        return $this->templateRenderer->loadTemplate('/auth/login', $this->data);
    }

    public function processLogin(Request $request)
    {
        return 'post login';
    }

    /**
     * @return bool
     */
    public function checkUserIsAuthenticated(): bool
    {
        return Auth::isAuthenticated();
    }
}
