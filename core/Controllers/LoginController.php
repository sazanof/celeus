<?php

namespace Vorkfork\Core\Controllers;

use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    protected bool $useTemplateRenderer = true;

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function getLogin(Request $request): string
    {
        return $this->templateRenderer->loadTemplate('/auth/login', ['test'=>'Test Var']);
    }

    public function processLogin(Request $request)
    {
        return 'post login';
    }
}
