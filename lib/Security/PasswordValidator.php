<?php

namespace Vorkfork\Security;

use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

class PasswordValidator
{
	public static function isDifficult($password)
	{
		return (bool)preg_match('/^[A-z0-9{!*&#@}]+$/', $password);
	}

	public static function validate(string $password, string $repeatPassword)
	{
		$validator = Validation::createValidator();
		$violations = $validator->validate($password, [
			new Length(['min' => 8]),
			new NotBlank(),
			new EqualTo($repeatPassword)
		]);

		if (count($violations) > 0) {
			throw new ValidationFailedException('password', $violations);
		}

		return true;
	}

}