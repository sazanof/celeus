<?php

namespace Vorkfork\Constraints;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Vorkfork\Security\PasswordValidator;

class PasswordIsDifficultValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint): void
	{
		if (!$constraint instanceof PasswordIsDifficult) {
			throw new UnexpectedTypeException($constraint, PasswordIsDifficult::class);
		}

		if (null === $value || '' === $value) {
			return;
		}

		if (!is_string($value)) {
			throw new UnexpectedValueException($value, 'string');
		}
		$request = Request::createFromGlobals();
		if (!empty($request->getContent())) {
			$request = $request->toArray();
			if (isset($request['password']) && !PasswordValidator::isDifficult($request['password'])) {
				$this->context->buildViolation($constraint->message)
					->addViolation();
			}
		}

	}
}