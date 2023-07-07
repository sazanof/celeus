<?php

namespace Vorkfork\Core\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Vorkfork\Core\Repositories\JobRepository;

#[ORM\Entity(repositoryClass: JobRepository::class)]
#[ORM\Table(name: '`jobs`')]
#[ORM\HasLifecycleCallbacks]
class Job
{
	#[ORM\Id]
	#[ORM\Column(type: Types::BIGINT)]
	#[ORM\GeneratedValue]
	private int $id;

	#[ORM\Column(type: Types::STRING)]
	private string $class;

	#[ORM\Column(type: Types::STRING)]
	private string $arguments;

	#[ORM\Column(type: Types::INTEGER)]
	private int $status;

	const STATUS_NEW = 1;

	const STATUS_RUNNING = 2;

	const STATUS_FINISHED = 3;

	const STATUS_FAILED = 4;

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getClass(): string
	{
		return $this->class;
	}

	/**
	 * @param string $class
	 */
	public function setClass(string $class): void
	{
		$this->class = $class;
	}

	/**
	 * @return string
	 */
	public function getArguments(): string
	{
		return $this->arguments;
	}

	/**
	 * @param string $arguments
	 */
	public function setArguments(string $arguments): void
	{
		$this->arguments = $arguments;
	}

	/**
	 * @return int
	 */
	public function getStatus(): int
	{
		return $this->status;
	}

	/**
	 * @param int $status
	 */
	public function setStatus(int $status): void
	{
		$this->status = $status;
	}
}
