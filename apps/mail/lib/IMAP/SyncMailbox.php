<?php

namespace Vorkfork\Apps\Mail\IMAP;

class SyncMailbox
{
	protected Mailbox $mailbox;

	public function __construct(Mailbox $mailbox)
	{
		$this->mailbox = $mailbox;
	}

	/**
	 * @return Mailbox
	 */
	public function getMailbox(): Mailbox
	{
		return $this->mailbox;
	}

	/**
	 * @param Mailbox $mailbox
	 */
	public function setMailbox(Mailbox $mailbox): void
	{
		$this->mailbox = $mailbox;
	}
}