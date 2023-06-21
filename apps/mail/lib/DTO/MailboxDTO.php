<?php

namespace Vorkfork\Apps\Mail\DTO;

use Vorkfork\Apps\Mail\IMAP\DTO\MailboxImapDTO;

class MailboxDTO extends MailboxImapDTO
{
	public int $id;

	public int $accountId;

	public int $uidValidity;
}