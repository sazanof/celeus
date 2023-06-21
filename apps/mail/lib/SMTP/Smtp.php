<?php

namespace Vorkfork\Apps\Mail\SMTP;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Vorkfork\Core\Exceptions\ErrorResponse;

class Smtp
{
	protected string $host;
	protected string $port;
	protected string $username;
	protected string $password;
	protected ?Mailer $mailer = null;
	protected ?TransportInterface $smtpTransport = null;
	protected SmtpConfig $config;
	protected static ?Smtp $instance = null;

	public function __construct(SmtpConfig $config)
	{
		$this->config = $config;
		$this->setHost($this->config->getHost());
		$this->setPort($this->config->getPort());
		$this->setUsername($this->config->getUsername());
		$this->setPassword($this->config->getPassword());
		$this->setSmtpTransport()->setMailer();
		self::$instance = $this;
	}

	/**
	 * @return string
	 */
	public function getHost(): string
	{
		return $this->host;
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
	public function getPort(): string
	{
		return $this->port;
	}

	/**
	 * @param string $port
	 */
	public function setPort(string $port): void
	{
		$this->port = $port;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
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
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	public function setSmtpTransport(): ErrorResponse|static
	{
		$this->smtpTransport = new SmtpTransport($this->getHost(), $this->getPort(), $this->config->isTls());
		$this->smtpTransport->setUsername($this->getUsername());
		$this->smtpTransport->setPassword($this->getPassword());
		$this->smtpTransport->start();
		return $this;
	}

	public function getSmtpTransport(): ?TransportInterface
	{
		return $this->smtpTransport;
	}

	public function setMailer(): static
	{
		$this->mailer = new Mailer($this->smtpTransport);
		return $this;
	}

	public static function getInstance(SmtpConfig $config): ?Smtp
	{
		if (is_null(self::$instance)) {
			return new self($config);
		}
		return self::$instance;
	}

	public static function test(SmtpConfig $config): void
	{
		Smtp::getInstance($config)->getSmtpTransport()->stop();
	}

	public static function start(): void
	{
		self::$instance->smtpTransport->start();
	}

	public static function stop(): void
	{
		self::$instance->smtpTransport->stop();
	}

	public static function check(SmtpConfig $config): bool
	{
		return self::$instance->smtpTransport instanceof SmtpTransport;
	}

}