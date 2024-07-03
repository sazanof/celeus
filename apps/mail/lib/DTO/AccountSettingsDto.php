<?php

namespace Vorkfork\Apps\Mail\DTO;

class AccountSettingsDto
{
	public int $id;

	public string $name;

	public string $email;

	public string $smtpUser;

	public string $smtpServer;

	public string $smtpPort;

	public string $smtpEncryption;

	public string $imapUser;

	public string $imapServer;

	public string $imapPort;

	public string $imapEncryption;
	
	public bool $isDefault;

}
