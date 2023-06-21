<?php

namespace Vorkfork\Apps\Mail\Models;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vorkfork\Apps\Mail\Repositories\MessageRepository;
use Vorkfork\Database\Entity;
use Vorkfork\Database\Trait\Timestamps;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webklex\PHPIMAP\Attribute;

/**
 * @method static MessageRepository repository()
 */
#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Index(columns: ['id'], name: 'id')]
#[ORM\Index(columns: ['message_id'], name: 'message_id')]
#[ORM\Table(name: '`mail_messages`')]
#[ORM\HasLifecycleCallbacks]
class Message extends Entity
{
	use Timestamps;

	#[ORM\Id]
	#[ORM\Column(type: Types::BIGINT)]
	#[ORM\GeneratedValue]
	private int $id;

	#[ORM\Column(name: 'mailbox_id', type: Types::INTEGER)]
	#[ORM\GeneratedValue]
	private int $mailboxId;

	#[ORM\Column(name: 'message_id', type: Types::STRING)]
	#[ORM\GeneratedValue]
	private string $messageId;

	#[ORM\Column(name: 'in_reply_to', type: Types::STRING)]
	#[ORM\GeneratedValue]
	private string $inReplyTo;

	#[ORM\Column(type: Types::TEXT, nullable: true)]
	#[ORM\GeneratedValue]
	private string $chain;

	#[ORM\Column(name: 'subject', type: Types::STRING, nullable: true)]
	#[ORM\GeneratedValue]
	private string $subject;

	#[ORM\Column(name: 'body', type: Types::TEXT, nullable: true)]
	#[ORM\GeneratedValue]
	private string $body;

	#[ORM\Column(name: 'preview', type: Types::STRING, nullable: true)]
	#[ORM\GeneratedValue]
	private string $preview;

	#[ORM\Column(name: 'sent_at', type: Types::DATETIME_MUTABLE, nullable: true)]
	#[ORM\GeneratedValue]
	private DateTime $sentAt;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	#[ORM\GeneratedValue]
	private bool $attachments;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	#[ORM\GeneratedValue]
	private bool $important;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	#[ORM\GeneratedValue]
	private bool $deleted;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	#[ORM\GeneratedValue]
	private bool $recent;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	#[ORM\GeneratedValue]
	private bool $seen;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	#[ORM\GeneratedValue]
	private bool $answered;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	#[ORM\GeneratedValue]
	private bool $draft;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	#[ORM\GeneratedValue]
	private bool $flagged;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	#[ORM\GeneratedValue]
	private bool $spam;

	#[ORM\Column(name: 'not_spam', type: Types::BOOLEAN, nullable: true)]
	#[ORM\GeneratedValue]
	private bool $notSpam;

	#[ORM\Column(name: 'local_message', type: Types::BOOLEAN, nullable: true)]
	#[ORM\GeneratedValue]
	private bool $localMessage;

	#[ORM\OneToMany(mappedBy: 'message', targetEntity: Recipient::class, orphanRemoval: true)]
	private Collection $recipients;

	protected array $fillable = [
		'id',
		'mailboxId',
		'messageId',
		'inReplyIo',
		'chain',
		'subject',
		'body',
		'preview',
		'sentAt',
		'attachments',
		'important',
		'deleted',
		'recent',
		'seen',
		'answered',
		'draft',
		'flagged',
		'spam',
		'notSpam',
		'localMessage'
	];

	public function __construct()
	{
		$this->recipients = new ArrayCollection();
		parent::__construct();
	}

	/**
	 * @return Collection
	 */
	public function getRecipients(): Collection
	{
		return $this->recipients;
	}

	/**
	 * @param Recipient $recipient
	 * @return $this
	 */
	public function addRecipient(Recipient $recipient): static
	{
		if (!$this->recipients->contains($recipient)) {
			$this->recipients[] = $recipient;
			$recipient->setMessage($this);
		}

		return $this;
	}

	/**
	 * @param Recipient $recipient
	 * @return $this
	 */
	public function removeRecipient(Recipient $recipient): static
	{
		if ($this->recipients->contains($recipient)) {
			$this->recipients->removeElement($recipient);
			// set the owning side to null (unless already changed)
			if ($recipient->getMessage() === $this) {
				$recipient->setMessage(null);
			}
		}
		return $this;
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return DateTime
	 */
	public function getSentAt(): DateTime
	{
		return $this->sentAt;
	}

	/**
	 * @return int
	 */
	public function getMailboxId(): int
	{
		return $this->mailboxId;
	}

	/**
	 * @return string
	 */
	public function getInReplyTo(): string
	{
		return $this->inReplyTo;
	}

	/**
	 * @return string
	 */
	public function getChain(): string
	{
		return $this->chain;
	}

	/**
	 * @return string
	 */
	public function getPreview(): string
	{
		return $this->preview;
	}

	/**
	 * @return string
	 */
	public function getSubject(): string
	{
		return $this->subject;
	}

	/**
	 * @return string
	 */
	public function getBody(): string
	{
		return $this->body;
	}

	/**
	 * @return string
	 */
	public function getMessageId(): string
	{
		return $this->messageId;
	}

	/**
	 * @param int $mailboxId
	 */
	public function setMailboxId(int $mailboxId): void
	{
		$this->mailboxId = $mailboxId;
	}

	/**
	 * @param string $subject
	 */
	public function setSubject(string $subject): void
	{
		$this->subject = $subject;
	}

	/**
	 * @param Attribute $chain
	 */
	public function setChain(Attribute $chain): void
	{
		$chain = array_filter($chain->toArray(), function ($el) {
			return !is_null($el) && filter_var($el, FILTER_VALIDATE_EMAIL) && $el !== 'null';
		});
		$this->chain = implode(',', $chain);
	}

	/**
	 * @param string $body
	 */
	public function setBody(string $body): void
	{
		$this->body = $body;
	}

	/**
	 * @param string $preview
	 */
	public function setPreview(string $preview): void
	{
		$this->preview = $preview;
	}

	/**
	 * @param bool $deleted
	 */
	public function setDeleted(bool $deleted): void
	{
		$this->deleted = $deleted;
	}

	/**
	 * @param bool $answered
	 */
	public function setAnswered(bool $answered): void
	{
		$this->answered = $answered;
	}

	/**
	 * @param bool $flagged
	 */
	public function setFlagged(bool $flagged): void
	{
		$this->flagged = $flagged;
	}

	/**
	 * @param bool $recent
	 */
	public function setRecent(bool $recent): void
	{
		$this->recent = $recent;
	}

	/**
	 * @param bool $attachments
	 */
	public function setAttachments(bool $attachments): void
	{
		$this->attachments = $attachments;
	}

	/**
	 * @param bool $draft
	 */
	public function setDraft(bool $draft): void
	{
		$this->draft = $draft;
	}

	/**
	 * @param bool $important
	 */
	public function setImportant(bool $important): void
	{
		$this->important = $important;
	}

	/**
	 * @param string $inReplyTo
	 */
	public function setInReplyTo(string $inReplyTo): void
	{
		$this->inReplyTo = $inReplyTo;
	}

	/**
	 * @param bool $local
	 */
	public function setLocalMessage(bool $local): void
	{
		$this->localMessage = $local;
	}

	/**
	 * @param string $messageId
	 */
	public function setMessageId(string $messageId): void
	{
		$this->messageId = $messageId;
	}

	/**
	 * @param bool $spam
	 */
	public function setSpam(bool $spam): void
	{
		$this->spam = $spam;
	}

	/**
	 * @param bool $notSpam
	 */
	public function setNotSpam(bool $notSpam): void
	{
		$this->notSpam = $notSpam;
	}

	/**
	 * @param bool $seen
	 */
	public function setSeen(bool $seen): void
	{
		$this->seen = $seen;
	}

	/**
	 * @param Attribute|string $sentAt
	 * @return void
	 * @throws \Exception
	 */
	public function setSentAt(Attribute|string $sentAt): void
	{
		if ($sentAt instanceof Attribute) {
			$sentAt = $sentAt->toString();
		}
		$this->sentAt = new DateTime($sentAt);
	}

	/**
	 * @return bool
	 */
	public function isAnswered(): bool
	{
		return $this->answered;
	}

	/**
	 * @return bool
	 */
	public function isAttachments(): bool
	{
		return $this->attachments;
	}

	/**
	 * @return bool
	 */
	public function isDeleted(): bool
	{
		return $this->deleted;
	}

	/**
	 * @return bool
	 */
	public function isDraft(): bool
	{
		return $this->draft;
	}

	/**
	 * @return bool
	 */
	public function isFlagged(): bool
	{
		return $this->flagged;
	}

	/**
	 * @return bool
	 */
	public function isImportant(): bool
	{
		return $this->important;
	}

	/**
	 * @return bool
	 */
	public function isLocalMessage(): bool
	{
		return $this->localMessage;
	}

	/**
	 * @return bool
	 */
	public function isNotSpam(): bool
	{
		return $this->notSpam;
	}

	/**
	 * @return bool
	 */
	public function isRecent(): bool
	{
		return $this->recent;
	}

	/**
	 * @return bool
	 */
	public function isSeen(): bool
	{
		return $this->seen;
	}

	/**
	 * @return bool
	 */
	public function isSpam(): bool
	{
		return $this->spam;
	}

}