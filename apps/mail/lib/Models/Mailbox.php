<?php

namespace Vorkfork\Apps\Mail\Models;

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
#[ORM\UniqueConstraint(name: 'name_validity', columns: ['name', 'uidvalidity'])]
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

	/** Many mailboxes have one product. This is the owning side. */
	#[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'mailboxes')]
	#[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id')]
	private Account|null $account = null;

	protected array $fillable = [
		'name',
		'delimiter',
		'total',
		'lastSync',
		'unseen',
		'uidValidity',
	];

	/**
	 * @param PrePersistEventArgs $args
	 * @return void
	 * @throws MailboxAlreadyExists
	 */
	#[ORM\PrePersist]
	public function checkMailboxOnDuplicate(PrePersistEventArgs $args): void
	{
		$count = ($this->repository->count(['name' => $this->name, 'uidValidity' => $this->uidValidity]));
		if ($count > 0) {
			throw new MailboxAlreadyExists($this->name);
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

}
