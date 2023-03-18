<?php

namespace Vorkfork\Core\Controllers;

use Vorkfork\Auth\Auth;
use Vorkfork\Core\Models\User;

class UserController extends Controller
{
    /**
     * @return User
     */
    public function getUser(): User
    {
        return Auth::getLoginUser();
    }
}