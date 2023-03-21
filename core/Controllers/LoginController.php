<?php

namespace Vorkfork\Core\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Vorkfork\Auth\Auth;
use Vorkfork\Core\Exceptions\UserNotFoundException;
use Vorkfork\Core\Models\User;
use Vorkfork\DTO\UserDto;
use Vorkfork\Security\Str;
use Vorkfork\Serializer\JsonSerializer;

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

    /**
     * @return User|null
     */
    public function checkUserIsAuthenticated(): User|null
    {
        return Auth::isAuthenticated() ? Auth::user() : null;
    }

    /**
     * @throws UserNotFoundException
     */
    public function logIn(Request $request): null|UserDto
    {
        $credentials = $request->toArray();
        $username = $credentials['username'];
        $password = $credentials['password'];
        if (Str::containsLetters($username)) {
            Auth::login($username, $password);
            $user = Auth::user();
            $userDto = $user->toDto(UserDto::class);
            //$userDto->set('groups', [1, 2, 3]);
            //dd($userDto);
            return $userDto;
        }
        //$username = $credentials
        return null;
    }

    /**
     * @return bool
     */
    public function logOut()
    {
        return Auth::logout();
    }
}
