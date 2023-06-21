<?php

namespace Vorkfork\Apps\Mail\SMTP;

use Symfony\Component\Mailer\Transport\Dsn;

class SmtpConfig
{
	protected string $host;
	protected string $port;
	protected string $username;
	protected string $password;
	protected string $scheme;
	protected bool $verifyPeer;
	protected bool $tls = false;

	public function __construct(string $host, string $port, string $username, string $password, bool $tls = false, $verifyPeer = true)
	{
		$this->host = $host;
		$this->port = $port;
		$this->username = $username;
		$this->password = $password;
		$this->verifyPeer = $verifyPeer;
		$this->tls = $tls;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getHost(): string
	{
		return $this->host;
	}

	/**
	 * @return string
	 */
	public function getPort(): string
	{
		return $this->port;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @return bool
	 */
	public function isTls(): bool
	{
		return $this->tls;
	}

	/**
	 * @return string
	 */
	public function getScheme(): string
	{
		return $this->isTls() ? 'smtps://' : 'smtp://';
	}

	public function toDsnString()
	{
		$dsn = $this->getScheme() . $this->getUsername() . ':' . $this->getPassword() . '@' . $this->getHost() . ':' . $this->getPort();
		if (!$this->verifyPeer) {
			$dsn .= '?verify_peer=false';
		}
		return $dsn;
	}
}