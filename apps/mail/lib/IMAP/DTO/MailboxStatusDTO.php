<?php

namespace Vorkfork\Apps\Mail\IMAP\DTO;

use Vorkfork\DTO\BaseDto;

class MailboxStatusDTO extends BaseDto
{
	public int $flags;

	public int $messages;

	public int $recent;

	public int $unseen;

	public int $uidnext;

	public int $uidvalidity;
}