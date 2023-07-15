<?php

namespace Vorkfork\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Vorkfork\Core\Models\Job;

class JobStatusResponse extends Response
{
	protected Job $job;
	protected int $code;

	public function __construct(Job $job, int $code = 201)
	{
		parent::__construct();
		$this->job = $job;
		$this->code = $code;
		return (new JsonResponse([
			'status' => $job->getStatus()
		], $this->code))->send();
	}
}
