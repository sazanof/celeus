<?php

namespace Vorkfork\Constraints;

use Symfony\Component\Validator\Constraint;

class PasswordEquals extends Constraint
{
	public string $message = 'The entered passwords do not match.';
}