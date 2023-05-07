<?php

namespace Vorkfork\Apps\Mail\Models;

use DateTime;
use Symfony\Component\Serializer\Annotation\Ignore;
use Vorkfork\Apps\Mail\Repositories\MailAccountsRepository;
use Vorkfork\Core\Models\User;
use Vorkfork\Database\Entity;
use Vorkfork\Database\Trait\Timestamps;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @method static MailAccountsRepository repository()
 */
#[ORM\Entity(repositoryClass: MailAccountsRepository::class)]
#[ORM\Index(columns: ['id'], name: 'id')]
#[ORM\Table(name: '`mail_accounts`')]
#[ORM\HasLifecycleCallbacks]
class Account extends Entity
{
	use Timestamps;

	#[ORM\Id]
	#[ORM\Column(type: Types::BIGINT)]
	#[ORM\GeneratedValue]
	private int $id;

	#[ORM\OneToOne(targetEntity: User::class)]
	#[ORM\JoinColumn(name: 'user', referencedColumnName: 'username')]
	private User|null $user = null;

	#[ORM\Column(type: Types::STRING, unique: true)]
	private string $email;

	#[ORM\Column(type: Types::STRING)]
	private string $name;

	#[Ignore]
	#[ORM\Column(name: 'smtp_user', type: Types::STRING)]
	private string $smtpUser;

	#[Ignore]
	#[ORM\Column(name: 'smtp_password', type: Types::STRING)]
	private string $smtpPassword;

	#[Ignore]
	#[ORM\Column(name: 'smtp_server', type: Types::STRING)]
	private string $smtpServer;

	#[Ignore]
	#[ORM\Column(name: 'smtp_port', type: Types::INTEGER)]
	private int $smtpPort;

	#[Ignore]
	#[ORM\Column(name: 'smtp_encryption', type: Types::STRING)]
	private string $smtpEncryption;

	#[Ignore]
	#[ORM\Column(name: 'imap_user', type: Types::STRING)]
	private string $imapUser;

	#[Ignore]
	#[ORM\Column(name: 'imap_password', type: Types::STRING)]
	private string $imapPassword;

	#[Ignore]
	#[ORM\Column(name: 'imap_server', type: Types::STRING)]
	private string $imapServer;

	#[Ignore]
	#[ORM\Column(name: 'imap_port', type: Types::INTEGER)]
	private int $imapPort;

	#[Ignore]
	#[ORM\Column(name: 'imap_encryption', type: Types::STRING)]
	private string $imapEncryption;

	#[ORM\Column(
		name: 'last_sync',
		type: Types::DATETIME_MUTABLE,
		nullable: false,
	)]
	private DateTime $lastSync;

	protected array $fillable = [
		'user',
		'email',
		'name',
		'smtp_user',
		'smtp_password',
		'smtp_server',
		'smtp_port',
		'smtp_encryption',
		'imap_user',
		'imap_password',
		'imap_server',
		'imap_port',
		'imap_encryption',
		'last_sync'
	];

}