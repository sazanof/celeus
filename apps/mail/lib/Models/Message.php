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

/**
 * @method static MessageRepository repository()
 */
#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Index(columns: ['id'], name: 'id')]
#[ORM\Index(columns: ['message_id'], name: 'message_id')]
#[ORM\Table(name: '`mail_messages`')]
#[ORM\HasLifecycleCallbacks]
class Message extends Entity {
	use Timestamps;

	#[ORM\Id]
	#[ORM\Column(type: Types::BIGINT)]
	#[ORM\GeneratedValue]
	private int $id;

	#[ORM\Column(name: 'mailbox_id', type: Types::INTEGER)]
	private int $mailboxId;

	#[ORM\Column(name: 'uid', type: Types::INTEGER)]
	private int $uid;

	#[ORM\Column(name: 'num', type: Types::INTEGER)]
	private int $num;

	#[ORM\Column(name: 'message_id', type: Types::STRING)]
	private string $messageId;

	#[ORM\Column(name: 'in_reply_to', type: Types::STRING, nullable: true)]
	private ?string $inReplyTo;

	#[ORM\Column(type: Types::TEXT, nullable: true)]
	private ?string $chain;

	#[ORM\Column(name: 'subject', type: Types::STRING, nullable: true)]
	private string $subject;

	#[ORM\Column(name: 'preview', type: Types::STRING, nullable: true)]
	private ?string $preview;

	#[ORM\Column(name: 'sent_at', type: Types::DATETIME_MUTABLE, nullable: true)]
	private DateTime $sentAt;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	private bool $attachments;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	private bool $important;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	private bool $deleted;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	private bool $recent;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	private bool $seen;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	private bool $answered;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	private bool $draft;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	private bool $flagged;

	#[ORM\Column(type: Types::BOOLEAN, nullable: true)]
	private bool $spam;

	#[ORM\Column(name: 'not_spam', type: Types::BOOLEAN, nullable: true)]
	private bool $notSpam;

	#[ORM\Column(name: 'local_message', type: Types::BOOLEAN, nullable: true)]
	private bool $localMessage;

	#[ORM\OneToMany(mappedBy: 'message', targetEntity: Recipient::class, orphanRemoval: true)]
	private Collection $recipients;

	protected array $fillable = [
		'id',
		'uid',
		'num',
		'mailboxId',
		'messageId',
		'inReplyIo',
		'chain',
		'subject',
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

	public function __construct() {
		$this->recipients = new ArrayCollection();
		parent::__construct();
	}

	/**
	 * @return Collection
	 */
	public function getRecipients(): Collection {
		return $this->recipients;
	}

	/**
	 * @return int
	 */
	public function getNum(): int {
		return $this->num;
	}

	/**
	 * @return int
	 */
	public function getUid(): int {
		return $this->uid;
	}

	/**
	 * @param int $num
	 */
	public function setNum(int $num): void {
		$this->num = $num;
	}

	/**
	 * @param int $uid
	 */
	public function setUid(int $uid): void {
		$this->uid = $uid;
	}

	/**
	 * @param Recipient $recipient
	 * @return $this
	 */
	public function addRecipient(Recipient $recipient): static {
		if(!$this->recipients->contains($recipient)){
			$this->recipients[] = $recipient;
			$recipient->setMessage($this);
		}

		return $this;
	}

	/**
	 * @param Recipient $recipient
	 * @return $this
	 */
	public function removeRecipient(Recipient $recipient): static {
		if($this->recipients->contains($recipient)){
			$this->recipients->removeElement($recipient);
			// set the owning side to null (unless already changed)
			if($recipient->getMessage() === $this){
				$recipient->setMessage(null);
			}
		}
		return $this;
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return DateTime
	 */
	public function getSentAt(): DateTime {
		return $this->sentAt;
	}

	/**
	 * @return int
	 */
	public function getMailboxId(): int {
		return $this->mailboxId;
	}

	/**
	 * @return ?string
	 */
	public function getInReplyTo(): ?string {
		return $this->inReplyTo;
	}

	/**
	 * @return ?string
	 */
	public function getChain(): ?string {
		return $this->chain;
	}

	/**
	 * @return ?string
	 */
	public function getPreview(): ?string {
		return $this->preview;
	}

	/**
	 * @return string
	 */
	public function getSubject(): string {
		return $this->subject;
	}

	/**
	 * @return string
	 */
	public function getMessageId(): string {
		return $this->messageId;
	}

	/**
	 * @param int $mailboxId
	 */
	public function setMailboxId(int $mailboxId): void {
		$this->mailboxId = $mailboxId;
	}

	/**
	 * @param string $subject
	 */
	public function setSubject(string $subject): void {
		$this->subject = $subject;
	}

	/**
	 * @param ?string $chain
	 */
	public function setChain(?string $chain): void {

		$this->chain = $chain;
	}

	/**
	 * @param ?string $preview
	 */
	public function setPreview(?string $preview): void {
		$this->preview = $preview;
	}

	/**
	 * @param bool $deleted
	 */
	public function setDeleted(bool $deleted): void {
		$this->deleted = $deleted;
	}

	/**
	 * @param bool $answered
	 */
	public function setAnswered(bool $answered): void {
		$this->answered = $answered;
	}

	/**
	 * @param bool $flagged
	 */
	public function setFlagged(bool $flagged): void {
		$this->flagged = $flagged;
	}

	/**
	 * @param bool $recent
	 */
	public function setRecent(bool $recent): void {
		$this->recent = $recent;
	}

	/**
	 * @param bool $attachments
	 */
	public function setAttachments(bool $attachments): void {
		$this->attachments = $attachments;
	}

	/**
	 * @param bool $draft
	 */
	public function setDraft(bool $draft): void {
		$this->draft = $draft;
	}

	/**
	 * @param bool $important
	 */
	public function setImportant(bool $important): void {
		$this->important = $important;
	}

	/**
	 * @param ?string $inReplyTo
	 */
	public function setInReplyTo(?string $inReplyTo): void {
		$this->inReplyTo = $inReplyTo;
	}

	/**
	 * @param bool $local
	 */
	public function setLocalMessage(bool $local): void {
		$this->localMessage = $local;
	}

	/**
	 * @param string $messageId
	 */
	public function setMessageId(string $messageId): void {
		$this->messageId = $messageId;
	}

	/**
	 * @param bool $spam
	 */
	public function setSpam(bool $spam): void {
		$this->spam = $spam;
	}

	/**
	 * @param bool $notSpam
	 */
	public function setNotSpam(bool $notSpam): void {
		$this->notSpam = $notSpam;
	}

	/**
	 * @param bool $seen
	 */
	public function setSeen(bool $seen): void {
		$this->seen = $seen;
	}

	/**
	 * @param DateTime $sentAt
	 * @return void
	 * @throws \Exception
	 */
	public function setSentAt(DateTime $sentAt): void {
		$this->sentAt = $sentAt;
	}

	/**
	 * @return bool
	 */
	public function isAnswered(): bool {
		return $this->answered;
	}

	/**
	 * @return bool
	 */
	public function isAttachments(): bool {
		return $this->attachments;
	}

	/**
	 * @return bool
	 */
	public function isDeleted(): bool {
		return $this->deleted;
	}

	/**
	 * @return bool
	 */
	public function isDraft(): bool {
		return $this->draft;
	}

	/**
	 * @return bool
	 */
	public function isFlagged(): bool {
		return $this->flagged;
	}

	/**
	 * @return bool
	 */
	public function isImportant(): bool {
		return $this->important;
	}

	/**
	 * @return bool
	 */
	public function isLocalMessage(): bool {
		return $this->localMessage;
	}

	/**
	 * @return bool
	 */
	public function isNotSpam(): bool {
		return $this->notSpam;
	}

	/**
	 * @return bool
	 */
	public function isRecent(): bool {
		return $this->recent;
	}

	/**
	 * @return bool
	 */
	public function isSeen(): bool {
		return $this->seen;
	}

	/**
	 * @return bool
	 */
	public function isSpam(): bool {
		return $this->spam;
	}

}
