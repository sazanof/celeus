<?php

class ErrorResponse extends \Symfony\Component\HttpFoundation\JsonResponse
{
	public function __construct(mixed $data = null, int $status = 500, array $headers = [], bool $json = false)
	{
		parent::__construct($data, $status, $headers, $json);
	}
}
