<?php

namespace Vorkfork\Apps\Mail\DTO;

use Vorkfork\Apps\Mail\IMAP\DTO\MailboxImapDTO;

class MailboxDTO extends MailboxImapDTO
{
	public int $id;

	public int $uidValidity;

	public int $total;

	public int $unseen;

	public string $path;

	public string $name;
}
