<?php

namespace Vorkfork\Constraints;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PasswordEqualsValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint): void
	{
		if (!$constraint instanceof PasswordEquals) {
			throw new UnexpectedTypeException($constraint, PasswordEquals::class);
		}

		if (null === $value || '' === $value) {
			return;
		}

		if (!is_string($value)) {
			throw new UnexpectedValueException($value, 'string');
		}

		$request = Request::createFromGlobals()->toArray();
		if (isset($request['password'])) {
			if ($request['password'] !== $request['repeatPassword']) {
				$this->context->buildViolation($constraint->message)
					->addViolation();
			}
		}

	}
}