<?php

namespace Vorkfork\Apps\Mail\IMAP;

use IMAP\Connection as IMAPConnection;
use Vorkfork\Apps\Mail\IMAP\DTO\MailboxHeadersImapDTO;
use Vorkfork\Apps\Mail\IMAP\DTO\MailboxImapDTO;
use Vorkfork\Apps\Mail\IMAP\DTO\MailboxStatusDTO;
use Vorkfork\Apps\Mail\IMAP\Exceptions\GetMailboxesException;
use Vorkfork\Apps\Mail\IMAP\Exceptions\ImapErrorException;
use Vorkfork\Security\Str;
use Vorkfork\Serializer\JsonSerializer;
use Webklex\PHPIMAP\Exceptions\AuthFailedException;
use Webklex\PHPIMAP\Exceptions\ConnectionFailedException;
use Webklex\PHPIMAP\Exceptions\FolderFetchingException;
use Webklex\PHPIMAP\Exceptions\GetMessagesFailedException;
use Webklex\PHPIMAP\Exceptions\ImapBadRequestException;
use Webklex\PHPIMAP\Exceptions\ImapServerErrorException;
use Webklex\PHPIMAP\Exceptions\ResponseException;
use Webklex\PHPIMAP\Exceptions\RuntimeException;
use Webklex\PHPIMAP\Folder;
use Webklex\PHPIMAP\Support\FolderCollection;
use Webklex\PHPIMAP\Support\MessageCollection;

class Mailbox
{
	const LATT_NOINFERIORS = 1; // It is not possible for any child levels of hierarchy to exist under this name; no child levels exist now and none can be created in the future
	const LATT_NOSELECT = 2; // This is only a container, not a mailbox - you cannot open it.
	const LATT_MARKED = 4; // This mailbox is marked. This means that it may contain new messages since the last time it was checked. Not provided by all IMAP servers.
	const LATT_UNMARKED = 8; // This mailbox is not marked, does not contain new messages. If either MARKED or UNMARKED is provided, you can assume the IMAP server supports this feature for this mailbox.
	const LATT_REFERRAL = 16; // This mailbox is a link to another remote mailbox (http://www.ietf.org/rfc/rfc2193.txt)
	const LATT_HASCHILDREN = 32; // This mailbox contains children.
	const LATT_HASNOCHILDREN = 64; // This mailbox not contain any children.

	protected ?Server $server = null;
	protected string $username;
	protected string $password;
	protected int $flags;
	protected array $options;
	protected ?object $connection = null;
	protected Folder $folder;

	/**
	 * @throws ImapErrorException
	 */
	public function __construct(Server $server,
	                            string $username,
	                            string $password
	)
	{
		$this->setServer($server);
		$this->open($username, $password);
	}

	/**
	 * @throws ImapErrorException
	 */
	public function open(string $username, string $password): ?static
	{
		Imap::open(
			$this->server,
			$username,
			$password,
		);
		return $this;
	}

	/**
	 * @throws ImapErrorException
	 */
	public function ping(): bool
	{
		return Imap::ping();
	}


	/**
	 * @param bool $hierarchical
	 * @return FolderCollection
	 * @throws AuthFailedException
	 * @throws ConnectionFailedException
	 * @throws FolderFetchingException
	 * @throws ImapBadRequestException
	 * @throws ImapServerErrorException
	 * @throws ResponseException
	 * @throws RuntimeException
	 */
	public function getMailboxes(bool $hierarchical = false): FolderCollection
	{
		return Imap::getMailboxes($hierarchical);
	}

	/**
	 * @param string $name
	 * @return Folder
	 */
	public function getFolderByPath(string $name): Folder
	{
		return Imap::getFolderByPath($name);
	}


	/**
	 * @param Folder $folder
	 * @return MessageCollection|null
	 * @throws AuthFailedException
	 * @throws ConnectionFailedException
	 * @throws GetMessagesFailedException
	 * @throws ImapBadRequestException
	 * @throws ResponseException
	 * @throws RuntimeException
	 */
	public function getAllMessages(): ?MessageCollection
	{
		return Imap::findAll($this->folder);
	}

	public function getMessagesByPage(int $limit = 50, int $page = 1): ?\Illuminate\Pagination\LengthAwarePaginator
	{
		return Imap::paginate($this->folder, $limit, $page);
	}

	/**
	 * @return MessageCollection
	 * @throws AuthFailedException
	 * @throws ConnectionFailedException
	 * @throws GetMessagesFailedException
	 * @throws ImapBadRequestException
	 * @throws ImapServerErrorException
	 * @throws ResponseException
	 * @throws RuntimeException
	 */
	public function getRecentMessages(): MessageCollection
	{
		return Imap::query($this->folder)->recent()->get();
	}

	/**
	 * @return MessageCollection
	 * @throws AuthFailedException
	 * @throws ConnectionFailedException
	 * @throws GetMessagesFailedException
	 * @throws ImapBadRequestException
	 * @throws ImapServerErrorException
	 * @throws ResponseException
	 * @throws RuntimeException
	 */
	public function getUnseen(): MessageCollection
	{
		return Imap::query($this->folder)->unseen()->get();
	}

	/**
	 * @param Server $server
	 */
	public function setServer(Server $server): void
	{
		$this->server = $server;
	}

	/**
	 * @return Server
	 */
	public function getServer(): Server
	{
		return $this->server;
	}

	/**
	 * @param string $password
	 */
	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $username
	 */
	public function setUsername(string $username): void
	{
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @param int $flags
	 */
	public function setFlags(int $flags): void
	{
		$this->flags = $flags;
	}

	/**
	 * @return int
	 */
	public function getFlags(): int
	{
		return $this->flags;
	}

	/**
	 * @param Folder $folder
	 */
	public function setFolder(Folder $folder): void
	{
		$this->folder = $folder;
	}

	/**
	 * @return Folder
	 */
	public function getFolder(): Folder
	{
		return $this->folder;
	}

}
