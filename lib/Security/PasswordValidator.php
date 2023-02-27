<?php

namespace Vorkfork\Security;

class PasswordValidator
{
    public static function isDifficult($password){
        return (bool)preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password);
    }
}