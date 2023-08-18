<?php

namespace Vorkfork\Apps\Mail\IMAP;

use ReflectionException;
use Sazanof\PhpImapSockets\Collections\MailboxCollection;
use Sazanof\PhpImapSockets\Collections\MessageCollection;
use Sazanof\PhpImapSockets\Connection;
use Sazanof\PhpImapSockets\Exceptions\ConnectionException;
use Sazanof\PhpImapSockets\Exceptions\LoginFailedException;
use Sazanof\PhpImapSockets\Exceptions\NoResultsException;
use Sazanof\PhpImapSockets\Models\Paginator;
use Sazanof\PhpImapSockets\Query\FetchQuery;
use Sazanof\PhpImapSockets\Models\Mailbox as PISMailbox;
use Sazanof\PhpImapSockets\Query\SearchQuery;

class Mailbox {
	protected ?Server $server = null;
	protected Connection $connection;
	protected string $username;
	protected string $password;
	protected array $options;
	protected PISMailbox $folder;
	protected FetchQuery $fetchQuery;
	protected SearchQuery $searchQuery;
	protected ?Paginator $paginator = null;

	/**
	 * @param Server $server
	 * @param string $username
	 * @param string $password
	 * @throws LoginFailedException
	 * @throws ReflectionException
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
		$this->setFolder(
			$this->getConnection()->getMailboxByPath(
				$this->getServer()->getMailbox()
			)
		);
		$this->fetchQuery = new FetchQuery();
		$this->searchQuery = new SearchQuery();
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
	 * @return mixed|null
	 * @throws ConnectionException
	 * @throws ReflectionException
	 */
	public function getFolderByPath(string $name) {
		return $this->getConnection()->getMailboxByPath($name);
	}

	/**
	 * @param int $limit
	 * @param int $page
	 * @param string $order
	 * @return Paginator
	 * @throws ReflectionException
	 * @throws NoResultsException
	 * @throws \Exception
	 */
	public function getMessagesByPage(int $limit = 50, int $page = 1, string $order = 'DESC'): Paginator {
		$this->folder = $this
			->getFolder();
		$nums = $this->folder->select()
			->search($this->searchQuery->all())
			->setOrderDirection($order)
			->msgNums();
		return new Paginator($nums, $this->folder, $page, $limit);
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
	 * @param PISMailbox $folder
	 */
	public function setFolder(PISMailbox $folder): void {
		$this->folder = $folder;
	}

	/**
	 * @return PISMailbox
	 */
	public function getFolder(): PISMailbox {
		return $this->folder;
	}

}
