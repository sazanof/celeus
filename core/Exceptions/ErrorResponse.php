<?php

namespace Vorkfork\Core\Exceptions;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorResponse extends JsonResponse
{
	public function __construct(mixed $message, int $status = 500, array $headers = [], bool $json = false)
	{
		parent::__construct([
			'success' => false,
			'message' => $message
		], $status, $headers, $json);
	}
}
