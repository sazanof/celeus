<?php

namespace Vorkfork\Apps\Mail\IMAP;

use ReflectionException;
use Sazanof\PhpImapSockets\Collections\MailboxCollection;
use Sazanof\PhpImapSockets\Connection;
use Sazanof\PhpImapSockets\Exceptions\ConnectionException;
use Sazanof\PhpImapSockets\Exceptions\LoginFailedException;

class Mailbox {
	protected ?Server $server = null;
	protected Connection $connection;
	protected string $username;
	protected string $password;
	protected int $flags;
	protected array $options;
	protected Folder $folder;

	/**
	 * @param Server $server
	 * @param string $username
	 * @param string $password
	 * @throws LoginFailedException
	 * @throws ConnectionException
	 */
	public function __construct(
		Server $server,
		string $username,
		string $password
	) {
		$this->setServer($server);
		$connection = Connection::create($server->getHost(), $server->getPort());
		$this->setConnection($connection);
		$this->login($username, $password);
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return $this|null
	 * @throws LoginFailedException
	 */
	public function login(string $username, string $password): ?static {
		$this->connection->login($username, $password);
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isConnected(): bool {
		return $this->connection->isOpen();
	}

	/**
	 * @return bool
	 * @throws ReflectionException
	 */
	public function ping(): bool {
		return $this->connection->noop();
	}


	/**
	 * @param string $startPath
	 * @return MailboxCollection
	 * @throws ConnectionException
	 * @throws ReflectionException
	 */
	public function getMailboxes(string $startPath = ''): MailboxCollection {
		return $this->connection->listMailboxes($startPath);
	}

	/**
	 * @param string $startPath
	 * @return MailboxCollection
	 * @throws ReflectionException
	 */
	public function getMailboxesTree(string $startPath = ''): MailboxCollection {
		return $this->connection->listMailboxesTree($startPath);
	}

	/**
	 * @param string $name
	 * @return Folder
	 */
	public function getFolderByPath(string $name): Folder {
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
	public function getAllMessages(): ?MessageCollection {
		return Imap::findAll($this->folder);
	}

	public function getMessagesByPage(int $limit = 50, int $page = 1): ?\Illuminate\Pagination\LengthAwarePaginator {
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
	public function getRecentMessages(): MessageCollection {
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
	public function getUnseen(): MessageCollection {
		return Imap::query($this->folder)->unseen()->get();
	}

	/**
	 * @param Server $server
	 */
	public function setServer(Server $server): void {
		$this->server = $server;
	}

	/**
	 * @param Connection $connection
	 */
	public function setConnection(Connection $connection): void {
		$this->connection = $connection;
	}

	/**
	 * @return Connection
	 */
	public function getConnection(): Connection {
		return $this->connection;
	}

	/**
	 * @return Server
	 */
	public function getServer(): Server {
		return $this->server;
	}

	/**
	 * @param string $password
	 */
	public function setPassword(string $password): void {
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string {
		return $this->password;
	}

	/**
	 * @param string $username
	 */
	public function setUsername(string $username): void {
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string {
		return $this->username;
	}

	/**
	 * @param int $flags
	 */
	public function setFlags(int $flags): void {
		$this->flags = $flags;
	}

	/**
	 * @return int
	 */
	public function getFlags(): int {
		return $this->flags;
	}

	/**
	 * @param Folder $folder
	 */
	public function setFolder(Folder $folder): void {
		$this->folder = $folder;
	}

	/**
	 * @return Folder
	 */
	public function getFolder(): Folder {
		return $this->folder;
	}

}
