<?php

namespace Vorkfork\Core\Jobs;

use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Response;
use Vorkfork\Core\Exceptions\ErrorResponse;
use Vorkfork\Core\Models\Job as JobModel;
use Vorkfork\Response\JobStatusResponse;

class Job
{
	const STATUS_NEW = 1;

	const STATUS_RUNNING = 2;

	const STATUS_FINISHED = 3;

	const STATUS_FAILED = 4;

	protected string $targetClass;

	protected array $arguments;

	protected object $jobInstance;

	protected static Job|null $instance = null;

	/**
	 * @param string $targetClass
	 * @param array|string $arguments
	 */
	public function __construct(string $targetClass, array|string $arguments)
	{
		if (is_string($arguments)) {
			$arguments = json_decode($arguments, true);
		}
		$this->arguments = $arguments;
		$this->targetClass = $targetClass;
		/*$this->create();
		$this->run();*/
	}

	public static function getInstance(string $targetClass, array|string $arguments)
	{
		return new self($targetClass, $arguments);
	}

	/**
	 * @return \ReflectionClass
	 * @throws \ReflectionException
	 */
	public function create(): \ReflectionClass
	{
		$this->jobInstance = new \ReflectionClass($this->targetClass);
		return $this->jobInstance;
	}

	/**
	 * @return mixed
	 */
	public function run(): mixed
	{
		return $this->jobInstance->newInstanceArgs($this->arguments);
	}

	/**
	 * @param JobModel $job
	 * @return void
	 * @throws \ReflectionException
	 */
	public static function execute(JobModel $job)
	{
		$instance = self::getInstance($job->getClass(), $job->getArguments());
		$instance->create();
		$instance->run();
	}

	/**
	 * Push job to database table
	 * @param string $class
	 * @param array $args
	 * @return JobStatusResponse
	 */
	public static function push(string $class, array $args)
	{
		$instance = self::getInstance($class, $args);
		$jobModelInstance = JobModel::repository()->findJobByClass($class, $args);
		if (is_null($jobModelInstance)) {
			$job = $instance->createJob();
			$code = 201;
		} else {
			$job = $jobModelInstance;
			$code = 409;
		}
		return new JobStatusResponse($job, $code);
	}

	public static function dispatch(string $targetClass, array|string $arguments)
	{
		return new self($targetClass, $arguments);
	}

	private function createJob()
	{
		$job = new JobModel();
		$job->setClass($this->targetClass);
		$job->setArguments($this->arguments);
		$job->setStatus(self::STATUS_NEW);
		try {
			$job->save();
			return $job;
		} catch (MissingMappingDriverImplementation|OptimisticLockException $e) {
			//todo LOG
			dd($e);
		}

	}
}
