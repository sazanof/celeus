<?php

namespace Vorkfork\Apps\Mail\IMAP;

use Illuminate\Pagination\LengthAwarePaginator;
use IMAP\Connection as IMAPConnection;
use Sabre\DAV\Exception;
use Vorkfork\Apps\Mail\IMAP\DTO\MailboxImapDTO;
use Vorkfork\Apps\Mail\IMAP\Exceptions\GetMailboxesException;
use Vorkfork\Apps\Mail\IMAP\Exceptions\ImapErrorException;
use Vorkfork\Security\Str;
use Vorkfork\Serializer\JsonSerializer;
use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Exceptions\AuthFailedException;
use Webklex\PHPIMAP\Exceptions\ConnectionFailedException;
use Webklex\PHPIMAP\Exceptions\FolderFetchingException;
use Webklex\PHPIMAP\Exceptions\GetMessagesFailedException;
use Webklex\PHPIMAP\Exceptions\ImapBadRequestException;
use Webklex\PHPIMAP\Exceptions\ImapServerErrorException;
use Webklex\PHPIMAP\Exceptions\ResponseException;
use Webklex\PHPIMAP\Exceptions\RuntimeException;
use Webklex\PHPIMAP\Folder;
use Webklex\PHPIMAP\Query\WhereQuery;
use Webklex\PHPIMAP\Support\FolderCollection;
use Webklex\PHPIMAP\Support\MessageCollection;

class Imap
{
	protected static mixed $connection = null;
	protected static ?string $connectionString = null;
	protected static ClientManager $clientManager;
	protected static Client $client;

	/**
	 * @param Server $server
	 * @param string $username
	 * @param string $password
	 * @return void
	 * @throws AuthFailedException
	 * @throws ConnectionFailedException
	 * @throws ImapBadRequestException
	 * @throws ImapServerErrorException
	 * @throws ResponseException
	 * @throws RuntimeException
	 * @throws \Webklex\PHPIMAP\Exceptions\MaskNotFoundException
	 */
	public static function open(Server $server,
	                            string $username,
	                            string $password,
	)
	{
		// TODO move config to file, generate tags list
		self::$clientManager = new ClientManager([
			'flags' => null,
			'fetch' => FT_PEEK,
		]);
		$client = self::$clientManager->make([
			'host' => $server->getHost(),
			'port' => $server->getPort(),
			'encryption' => $server->getEncryption(),
			'validate_cert' => $server->isValidateCert(),
			'username' => $username,
			'password' => $password,
			'protocol' => 'imap'
		]);
		$client->connect();
		self::$client = $client;
	}

	/**
	 * @param string|null $folderName
	 * @return bool
	 * @throws AuthFailedException
	 * @throws ConnectionFailedException
	 * @throws ImapBadRequestException
	 * @throws ImapServerErrorException
	 * @throws ResponseException
	 * @throws RuntimeException
	 */
	public static function reopen(string $folderName = null): bool
	{
		self::$client->openFolder($folderName);
	}

	/**
	 * @param IMAPConnection|null $connection
	 * @return bool
	 */
	public static function close(IMAPConnection $connection = null): bool
	{
		$res = @imap_close(is_null($connection) ? self::$connection : $connection);
		self::triggerError();
		return $res;
	}

	/**
	 * @param IMAPConnection|null $connection
	 * @return bool
	 * @throws AuthFailedException
	 * @throws ConnectionFailedException
	 * @throws ImapBadRequestException
	 * @throws ImapServerErrorException
	 * @throws ResponseException
	 * @throws RuntimeException
	 */
	public static function ping(): bool
	{
		return self::$client->checkConnection();
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
	public static function getMailboxes(bool $hierarchical = false): FolderCollection
	{
		return self::$client->getFoldersWithStatus($hierarchical);
	}

	public static function getFolderByPath(string $name): Folder
	{
		$folder = self::$client->getFolderByPath($name);
		$folder->status = $folder->getStatus();
		return $folder;
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
	public static function findAll(Folder $folder): ?\Webklex\PHPIMAP\Support\MessageCollection
	{
		try {
			$query = $folder->query();
			return $query->all()->get();

		} catch (ImapServerErrorException $exception) {
			return null;
		}
	}

	/**
	 * @throws RuntimeException
	 * @throws ResponseException
	 * @throws GetMessagesFailedException
	 * @throws ImapBadRequestException
	 * @throws ConnectionFailedException
	 * @throws AuthFailedException
	 */
	public static function paginate(Folder $folder, int $limit, $page = 1, bool $fetchBody = true): ?LengthAwarePaginator
	{
		try {
			return self::query($folder)->setFetchBody($fetchBody)->all()->paginate($limit, $page);

		} catch (ImapServerErrorException $exception) {
			return null;
		}
	}

	/**
	 * @param Folder $folder
	 * @return WhereQuery|null
	 * @throws AuthFailedException
	 * @throws ConnectionFailedException
	 * @throws ImapBadRequestException
	 * @throws ResponseException
	 * @throws RuntimeException
	 */
	public static function query(Folder $folder): ?WhereQuery
	{
		try {
			return $folder->query();

		} catch (ImapServerErrorException) {
			return null;
		}
	}

	/**
	 * @param int $msgNum
	 * @return false|int
	 * @throws ImapErrorException
	 */
	public static function getUid(int $msgNum): bool|int
	{
		$res = @imap_uid(self::$connection, $msgNum);
		self::triggerError();
		return $res;
	}

	/**
	 * @param int $msgNumber
	 * @return bool|\stdClass
	 * @throws ImapErrorException
	 */
	public static function fetchHeader(int $msgNumber): bool|\stdClass
	{
		$res = @imap_body(self::$connection, $msgNumber, FT_PEEK);
		dd($res);
		$res = @imap_headerinfo(self::$connection, $msgNumber);
		self::triggerError();
		return $res;
	}

	/**
	 * @param int $msgNumber
	 * @param int $flags
	 * @return false|string
	 * @throws ImapErrorException
	 */
	public static function fetchBody(int $msgNumber, int $flags = FT_PEEK): bool|string
	{
		$res = @imap_body(self::$connection, $msgNumber, $flags);
		self::triggerError();
		return $res;
	}

	/**
	 * @param string $mime_encoded_text
	 * @return string
	 */
	public static function toUtf8(string $mime_encoded_text): string
	{
		return imap_utf8($mime_encoded_text);
	}

	/**
	 * Prepare DTO after getMailboxes
	 * @param array $mailboxes
	 * @return array|MailboxImapDTO
	 */
	protected static function prepareMailboxes(array $mailboxes): array|MailboxImapDTO
	{
		return JsonSerializer::deserializeArrayStatic($mailboxes, MailboxImapDTO::class);
	}

	/**
	 * @param int $attributes
	 * @return array
	 */
	public static function parseAttributes(int $attributes): array
	{
		$bin = decbin($attributes);
		$setAttribute = array();

		if ($bin >= 1000000) {
			$setAttribute[] = Mailbox::LATT_HASNOCHILDREN;
			$bin = $bin - 1000000;
		}

		if ($bin >= 100000) {
			$setAttribute[] = Mailbox::LATT_HASCHILDREN;
			$bin = $bin - 100000;
		}

		if ($bin >= 10000) {
			$setAttribute[] = Mailbox::LATT_REFERRAL;
			$bin = $bin - 10000;
		}

		if ($bin >= 1000) {
			$setAttribute[] = Mailbox::LATT_UNMARKED;
			$bin = $bin - 1000;
		}

		if ($bin >= 100) {
			$setAttribute[] = Mailbox::LATT_MARKED;
			$bin = $bin - 100;
		}

		if ($bin >= 10) {
			$setAttribute[] = Mailbox::LATT_NOSELECT;
			$bin = $bin - 10;
		}

		if ($bin >= 1) {
			$setAttribute[] = Mailbox::LATT_NOINFERIORS;
			$bin = $bin - 1;
		}

		return $setAttribute;
	}

	public static function convertFromUtf8(string $string): bool|string
	{
		return imap_utf8_to_mutf7($string);
	}

	public static function convertToUtf8(string $string): bool|string
	{
		return imap_mutf7_to_utf8($string);
	}

	public static function cutHostString(string $string)
	{
		return Str::replace('/{(.*)}/', '', $string);
	}

	public static function trimHost()
	{
		return Str::replace('({.+})', '', self::$connectionString);
	}

	/**
	 * @throws ImapErrorException
	 */
	public static function triggerError()
	{
		$error = self::getErrors();
		if (false !== $error) {
			throw new ImapErrorException($error[array_key_last($error)]);
		}
	}

	/**
	 * @return false|string
	 */
	protected static function getLastError()
	{
		return @imap_last_error();
	}

	/**
	 * @return array|false
	 */
	protected static function getErrors()
	{
		return @imap_errors();
	}

	public static function getFlags(int $uid): array
	{
		$result = self::send("FETCH $uid (FLAGS)");
		preg_match_all("|\\* \\d+ FETCH \\(FLAGS \\((.*)\\)\\)|", $result[0], $matches);
		if (isset($matches[1][0])) {
			return explode(' ', $matches[1][0]);
		} else {
			return [];
		}
	}

	private static function send(string $cmd, string $uid = '.')
	{
		$query = "$uid $cmd\r\n";
		$count = fwrite(self::$connection, $query);
		if ($count === strlen($query)) {
			return self::gets();
		} else {
			throw new \Exception("Unable to execute '$cmd' command");
		}
	}

	private static function gets()
	{
		$result = [];

		while (substr($str = fgets(self::$connection), 0, 1) == '*') {
			$result[] = substr($str, 0, -2);
		}
		$result[] = substr($str, 0, -2);

		return $result;
	}

}