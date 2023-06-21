<?php

namespace Vorkfork\Apps\Mail\IMAP\DTO;

use DateTime;
use Exception;
use Vorkfork\Apps\Mail\IMAP\Imap;
use Vorkfork\Serializer\JsonSerializer;

class MailboxHeadersImapDTO
{
	public string $messageId;
	public string $toAddress;
	public string $subject;
	public string $fromAddress;
	public string $replyToAddress;
	public string $senderAddress;
	public DateTime $sentAt;
	public DateTime $receivedAt;
	public int $size;
	public bool $unseen;
	public bool $recent;
	public bool $flagged;
	public bool $answered;
	public bool $deleted;
	public bool $draft;

	/**
	 * Transform udate param to sentAt
	 * @param int $date
	 * @return void
	 */
	public function setUdate(int $date): void
	{
		$dt = new DateTime();
		$dt->setTimestamp($date);
		$this->sentAt = $dt;
	}

	public function setDate(string $date)
	{
		$this->receivedAt = new DateTime($date);
	}

	public function setSubject(string $subject)
	{
		$this->subject = Imap::toUtf8($subject);
	}

	/**
	 * @param string $unseen
	 */
	public function setUnseen(string $unseen): void
	{
		$this->unseen = $unseen === 'U';
	}

	/**
	 * @return bool
	 */
	public function isUnseen(): bool
	{
		return $this->unseen;
	}

	/**
	 * @param string $messageId
	 */
	public function setMessageId(string $messageId): void
	{
		$this->messageId = $messageId;
	}

	/**
	 * @param string $toAddress
	 */
	public function setToAddress(string $toAddress): void
	{
		$this->toAddress = Imap::toUtf8($toAddress);
	}

	/**
	 * @param string $fromAddress
	 */
	public function setFromAddress(string $fromAddress): void
	{
		$this->fromAddress = Imap::toUtf8($fromAddress);
	}

	/**
	 * @param string $replyToAddress
	 */
	public function setReplyToAddress(string $replyToAddress): void
	{
		$this->replyToAddress = Imap::toUtf8($replyToAddress);
	}

	/**
	 * @param string $senderAddress
	 */
	public function setSenderAddress(string $senderAddress): void
	{
		$this->senderAddress = Imap::toUtf8($senderAddress);
	}

	/**
	 * @param string $recent
	 */
	public function setRecent(string $recent): void
	{
		$this->recent = $recent === 'R';
	}

	/**
	 * @param string $flagged
	 */
	public function setFlagged(string $flagged): void
	{
		$this->flagged = $flagged == 'F';
	}

	/**
	 * @param string $answered
	 */
	public function setAnswered(string $answered): void
	{
		$this->answered = $answered === 'A';
	}

	/**
	 * @param string $deleted
	 */
	public function setDeleted(string $deleted): void
	{
		$this->deleted = $deleted === 'D';
	}

	/**
	 * @param string $draft
	 */
	public function setDraft(string $draft): void
	{
		$this->draft = $draft === 'X';
	}
}