<?php

namespace Vorkfork\Apps\Mail\IMAP;

use Vorkfork\Security\Str;

class Server
{
	protected string $host;
	protected string $port;
	protected string $encryption;
	protected bool $validateCert;
	protected string $mailbox;

	public function __construct(string $host, int $port, string $mailbox = 'INBOX', string $encryption = 'none', bool $validateCert = true)
	{
		$this->setHost($host);
		$this->setPort($port);
		$this->setMailbox($mailbox);
		$this->setEncryption($encryption);
		$this->setValidateCert($validateCert);
	}

	/**
	 * @param string $host
	 */
	public function setHost(string $host): void
	{
		$this->host = $host;
	}

	/**
	 * @return string
	 */
	public function getHost(): string
	{
		return $this->host;
	}

	/**
	 * @param int $port
	 */
	public function setPort(int $port): void
	{
		$this->port = $port;
	}

	/**
	 * @return int
	 */
	public function getPort(): int
	{
		return $this->port;
	}

	/**
	 * @param string $mailbox
	 */
	public function setMailbox(string $mailbox): void
	{
		$this->mailbox = $mailbox;
	}

	/**
	 * @return string
	 */
	public function getMailbox(): string
	{
		return $this->mailbox;
	}

	/**
	 * @param array $options
	 */
	public function setOptions(array $options): void
	{
		$this->options = $options;
	}


	/**
	 * @param string $encryption
	 */
	public function setEncryption(string $encryption): void
	{
		$this->encryption = $encryption;
	}

	/**
	 * @return string
	 */
	public function getEncryption(): string
	{
		return $this->encryption;
	}

	/**
	 * @param bool $validateCert
	 */
	public function setValidateCert(bool $validateCert): void
	{
		$this->validateCert = $validateCert;
	}

	/**
	 * @return bool
	 */
	public function isValidateCert(): bool
	{
		return $this->validateCert;
	}
}