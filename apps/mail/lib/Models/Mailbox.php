<?php

namespace Vorkfork\Apps\Mail\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Vorkfork\Apps\Mail\Exceptions\MailboxAlreadyExists;
use Vorkfork\Apps\Mail\IMAP\DTO\MailboxImapDTO;
use Vorkfork\Database\Entity;
use Vorkfork\Database\Trait\Timestamps;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vorkfork\Apps\Mail\Repositories\MailboxRepository;

/**
 * @method static MailboxRepository repository()
 * @method MailboxImapDTO toDto(string $dtoClass)()
 */
#[ORM\Entity(repositoryClass: MailboxRepository::class)]
#[ORM\Index(columns: ['id'], name: 'id')]
#[ORM\UniqueConstraint(name: 'path_validity', columns: ['path', 'uidvalidity'])]
#[ORM\Table(name: '`mail_mailboxes`')]
#[ORM\HasLifecycleCallbacks]
class Mailbox extends Entity
{
	use Timestamps;

	#[ORM\Id]
	#[ORM\Column(type: Types::BIGINT)]
	#[ORM\GeneratedValue]
	protected int $id;

	#[ORM\Column(type: Types::STRING)]
	protected string $name;

	#[ORM\Column(type: Types::STRING)]
	protected string $path;

	#[ORM\Column(type: Types::STRING, length: 1)]
	protected string $delimiter;

	#[ORM\Column(type: Types::INTEGER)]
	protected string $total;

	#[ORM\Column(type: Types::INTEGER)]
	protected string $unseen;

	#[ORM\Column(name: 'uidvalidity', type: Types::BIGINT)]
	protected int $uidValidity;

	#[ORM\Column(name: 'last_sync', type: Types::DATETIME_MUTABLE)]
	protected \DateTime $lastSync;

	#[ORM\Column(type: Types::INTEGER, nullable: true)]
	protected int $position;

	#[ORM\Column(type: Types::STRING, nullable: true)]
	protected string $attributes;

	#[ORM\Column(name: 'sync_token', type: Types::STRING, nullable: true)]
	protected string $syncToken;

	/** Many mailboxes have one account. This is the owning side. */
	#[ORM\ManyToOne(targetEntity: Account::class, cascade: ['persist'], inversedBy: 'mailboxes')]
	#[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
	private Account|null $account = null;

	#[ORM\ManyToOne(targetEntity: Mailbox::class, cascade: ['persist'], inversedBy: 'children')]
	private ?Mailbox $parent = null;

	#[ORM\OneToMany(mappedBy: 'parent', targetEntity: Mailbox::class)]
	private ?Collection $children;


	protected array $fillable = [
		'attributes',
		'name',
		'delimiter',
		'total',
		'lastSync',
		'syncToken',
		'unseen',
		'uidValidity',
		'position',
		'path'
	];

	public function __construct()
	{
		$this->children = new ArrayCollection();
		parent::__construct();
	}

	/**
	 * @param PrePersistEventArgs $args
	 * @return void
	 * @throws MailboxAlreadyExists
	 */
	#[ORM\PrePersist]
	public function checkMailboxOnDuplicate(PrePersistEventArgs $args): void
	{
		$count = ($this->repository->count(['path' => $this->name, 'uidValidity' => $this->uidValidity]));
		if ($count > 0) {
			throw new MailboxAlreadyExists($this->path);
		}
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDelimiter(): string
	{
		return $this->delimiter;
	}

	/**
	 * @param string $delimiter
	 */
	public function setDelimiter(string $delimiter): void
	{
		$this->delimiter = $delimiter;
	}


	/**
	 * @return string
	 */
	public function getTotal(): string
	{
		return $this->total;
	}

	/**
	 * @param string $total
	 */
	public function setTotal(string $total): void
	{
		$this->total = $total;
	}

	/**
	 * @return string
	 */
	public function getUnseen(): string
	{
		return $this->unseen;
	}

	/**
	 * @param ?int $unseen
	 */
	public function setUnseen(?int $unseen): void
	{
		$this->unseen = intval($unseen);
	}

	/**
	 * @return \DateTime
	 */
	public function getLastSync(): \DateTime
	{
		return $this->lastSync;
	}

	/**
	 * @param \DateTime $lastSync
	 */
	public function setLastSync(\DateTime $lastSync): void
	{
		$this->lastSync = $lastSync;
	}

	/**
	 * @return int
	 */
	public function getUidValidity(): int
	{
		return $this->uidValidity;
	}

	/**
	 * @param int $uidValidity
	 */
	public function setUidValidity(int $uidValidity): void
	{
		$this->uidValidity = $uidValidity;
	}

	/**
	 * @return Account|null
	 */
	public function getAccount(): ?Account
	{
		return $this->account;
	}

	public function setAccount(?Account $account)
	{
		$this->account = $account;
		$account->addMailbox($this);
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPosition(): int
	{
		return $this->position;
	}

	/**
	 * @param int $position
	 */
	public function setPosition(int $position): void
	{
		$this->position = $position;
	}

	/**
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 * @param string $path
	 */
	public function setPath(string $path): void
	{
		$this->path = $path;
	}

	/**
	 * @param string $syncToken
	 */
	public function setSyncToken(string $syncToken): void
	{
		$this->syncToken = $syncToken;
	}

	/**
	 * @return string
	 */
	public function getSyncToken(): string
	{
		return $this->syncToken;
	}

	/**
	 * @param Mailbox|null $parent
	 */
	public function setParent(?Mailbox $parent): void
	{
		$this->parent = $parent;
	}

	/**
	 * @return Mailbox|null
	 */
	public function getParent(): ?Mailbox
	{
		return $this->parent;
	}

	/**
	 * Get Child Mailboxes Collection
	 * @return Collection|null
	 */
	public function getChildren(): ?Collection
	{
		return $this->children;
	}

	/**
	 * Add child mailbox to Collection
	 * @param Mailbox|null $mailbox
	 * @return Mailbox
	 */
	public function addChild(?Mailbox $mailbox): static
	{
		if (!is_null($mailbox)) {
			$ind = $this->children->indexOf($mailbox);
			if ($ind === false) {
				$this->children->add($mailbox);
			} else {

				$this->children->set($ind, $mailbox);
			}
		}
		return $this;
	}

	/**
	 * Remove child mailbox to Collection
	 * @param Mailbox|null $mailbox
	 * @return Mailbox
	 */
	public function removeChild(?Mailbox $mailbox): static
	{
		if ($this->children->contains($mailbox)) {
			$this->children->removeElement($mailbox);
		}
		return $this;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getAttributes(): ArrayCollection
	{
		return new ArrayCollection(explode(',', $this->attributes));
	}

	/**
	 * @param ArrayCollection $attributes
	 */
	public function setAttributes(ArrayCollection $attributes): void
	{
		$this->attributes = implode(',', $attributes->toArray());
	}
}
