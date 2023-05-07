<?php

namespace Vorkfork\Core\Exceptions;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationExceptionResponse extends JsonResponse
{
	protected array $messages = [];

	public function __construct(ConstraintViolationList $violationList, int $status = 400, array $headers = [], bool $json = false)
	{
		$this->messages = $this->prepareMessagesArray($violationList);
		parent::__construct(['message' => $this->messages], $status, $headers, $json);
	}

	public function prepareMessagesArray(ConstraintViolationList $violationList): array
	{
		$ar = [];
		foreach ($violationList as $msg) {
			$ar[] = $msg->getMessage();
		}
		return $ar;
	}
}