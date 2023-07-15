<?php

namespace Vorkfork\Core\Repositories;

use Vorkfork\Core\Jobs\Job;

class JobRepository extends Repository
{
	public function findJobByClass(string $class, array $args)
	{
		return $this->findOneBy([
			'class' => $class,
			'arguments' => json_encode($args, JSON_UNESCAPED_UNICODE)
		]);
	}

	/**
	 * @return \Vorkfork\Core\Models\Job|null
	 */
	public function getLastNewJob()
	{
		return $this->findOneBy(['status' => Job::STATUS_NEW], ['id' => 'ASC']);
	}
}
