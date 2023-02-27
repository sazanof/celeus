<?php

namespace Vorkfork\Auth;

interface IAuthenticate
{
    public function login($username, $password);

    public function logout();

    public function check();

    public function getLoginUser();

    public function getLoginUserID();
}