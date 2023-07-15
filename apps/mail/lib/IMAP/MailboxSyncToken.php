<?php

namespace Vorkfork\Apps\Mail\IMAP;

use DateTime;
use Exception;

class MailboxSyncToken
{
	public DateTime $start;
	public bool $success = false;
	public bool $finish = false;
	public int $page = 1;

	/**
	 * @param string $jsonTokenString
	 * @return $this
	 * @throws Exception
	 */
	public function fromJson(string $jsonTokenString): static
	{
		$data = json_decode($jsonTokenString, true);
		if (
			isset($data['start']) &&
			isset($data['success']) &&
			isset($data['finish']) &&
			isset($data['page'])
		) {
			$this->setStart(new DateTime($data['start']));
			$this->setPage(intval($data['page']));
			$this->setSuccess($data['success']);
			$this->setFinish($data['finish']);
		}
		return $this;
	}

	public function toJson()
	{
		return json_encode([
			'start' => $this->getStart()->format('Y-m-d H:i:s'),
			'page' => $this->getPage(),
			'finish' => $this->isFinish(),
			'success' => $this->isSuccess(),
		]);
	}

	public function create(DateTime $start, int $page, bool $success, bool $finish)
	{
		$this->start = $start;
		$this->page = $page;
		$this->success = $success;
		$this->finish = $finish;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPage(): int
	{
		return $this->page;
	}

	/**
	 * @return DateTime
	 */
	public function getStart(): DateTime
	{
		return $this->start;
	}

	/**
	 * @return bool
	 */
	public function isFinish(): bool
	{
		return $this->finish;
	}

	/**
	 * @return bool
	 */
	public function isSuccess(): bool
	{
		return $this->success;
	}

	/**
	 * @param int $page
	 * @return MailboxSyncToken
	 */
	public function setPage(int $page): static
	{
		$this->page = $page;
		return $this;
	}

	/**
	 * @param bool $finish
	 * @return MailboxSyncToken
	 */
	public function setFinish(mixed $finish): static
	{
		$this->finish = (boolean)$finish;
		return $this;
	}

	/**
	 * @param DateTime $start
	 * @return MailboxSyncToken
	 */
	public function setStart(DateTime $start): static
	{
		$this->start = $start;
		return $this;
	}

	/**
	 * @param bool $success
	 * @return MailboxSyncToken
	 */
	public function setSuccess(mixed $success): static
	{
		$this->success = (boolean)$success;
		return $this;
	}
}
