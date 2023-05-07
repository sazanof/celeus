<?php

namespace Vorkfork\Core\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Vorkfork\Auth\Auth;
use Vorkfork\Core\Exceptions\UserNotFoundException;
use Vorkfork\Core\Models\User;
use Vorkfork\DTO\BaseDto;
use Vorkfork\DTO\GroupDto;
use Vorkfork\DTO\UserDto;
use Vorkfork\Security\Acl;
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
	 * @return ?UserDto
	 */
	public function checkUserIsAuthenticated(): ?UserDto
	{
		if (Auth::isAuthenticated()) {
			return Auth::user();
		} else {
			return null;
		}
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
			if (Auth::login($username, $password) instanceof UserDto) {
				return Auth::user();
			}
		}
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
