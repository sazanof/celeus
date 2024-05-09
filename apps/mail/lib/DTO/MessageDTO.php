<?php

namespace Vorkfork\Apps\Mail\DTO;

use Sazanof\PhpImapSockets\Collections\FlagsCollection;
use Vorkfork\Apps\Mail\Models\Recipient;
use Vorkfork\DTO\BaseDto;
use Vorkfork\Serializer\JsonSerializer;

class MessageDTO extends BaseDto
{
	public int $id;
	public int $accountId;
	public string $messageId;
	public string $subject;
	public ?string $preview;
	/** @var RecipientDTO $from */
	public RecipientDTO $from;
	/** @var RecipientDTO[] $to */
	public array $to;
	/** @var RecipientDTO[] $cc */
	public array $cc;
	/** @var RecipientDTO[] $bcc */
	public array $bcc;

	public bool $answered;
	public bool $draft;
	public bool $flagged;
	public bool $attachments;
	public bool $important;
	public bool $recent;
	public bool $seen;
	public bool $spam;
	public bool $notSpam;
	public \DateTime $sentAt;

	public function setSentAt($sentAt)
	{
		$dt = new \DateTime();
		$dt->setTimestamp($sentAt['timestamp']);
		$this->sentAt = $dt;
	}

	public function setRecipients(array $recipients)
	{
		$recipients = JsonSerializer::deserializeArrayStatic($recipients, RecipientDTO::class);
		$cc = [];
		$bcc = [];
		$to = [];
		/** @var RecipientDTO $recipient */
		foreach ($recipients as $recipient) {
			if ($recipient->type === Recipient::TYPE_FROM) {
				$this->from = $recipient;
			} elseif ($recipient->type === Recipient::TYPE_CC) {
				$cc[] = $recipient;
			} elseif ($recipient->type === Recipient::TYPE_BCC) {
				$bcc[] = $recipient;
			} elseif ($recipient->type === Recipient::TYPE_TO) {
				$to[] = $recipient;
			}
		}
		$this->to = $to;
		$this->cc = $cc;
		$this->bcc = $bcc;

	}
}
