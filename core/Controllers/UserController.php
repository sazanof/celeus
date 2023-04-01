<?php

namespace Vorkfork\Core\Controllers;

use Vorkfork\Auth\Auth;
use Vorkfork\DTO\BaseDto;
use Vorkfork\DTO\UserDto;

class UserController extends Controller
{
	/**
	 * @return BaseDto
	 */
	public function getUser(): BaseDto
	{
		return Auth::getLoginUser()->toDto(UserDto::class);
	}
}