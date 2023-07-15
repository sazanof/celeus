<?php

namespace Vorkfork\Apps\Mail\Tests;

use PHPUnit\Framework\TestCase;
use Vorkfork\Apps\Mail\IMAP\MailboxSyncToken;

class MailboxSyncTokenTest extends TestCase
{
	protected MailboxSyncToken $mailboxSyncToken;
	protected string $str = '{"start":"2022-01-21 07:15:55","page":7,"finish":false,"success":true}';

	protected function setUp(): void
	{
		$this->mailboxSyncToken = new MailboxSyncToken();
		$this->mailboxSyncToken->fromJson($this->str);
	}

	public function testPage()
	{
		$this->assertTrue($this->mailboxSyncToken->getPage() === 7);
	}

	public function testStart()
	{
		$this->assertTrue($this->mailboxSyncToken->getStart() instanceof \DateTime);
	}

	public function testFinish()
	{
		$this->assertNotTrue($this->mailboxSyncToken->isFinish());
	}

	public function testSuccess()
	{
		$this->assertTrue($this->mailboxSyncToken->isSuccess());
	}

	public function testToJson()
	{
		$this->assertTrue($this->mailboxSyncToken->toJson() === $this->str);
	}
}
