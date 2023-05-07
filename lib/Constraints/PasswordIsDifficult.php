<?php

namespace Vorkfork\Constraints;

use Symfony\Component\Validator\Constraint;

class PasswordIsDifficult extends Constraint
{
	public string $message = 'The password is too simple.';
}