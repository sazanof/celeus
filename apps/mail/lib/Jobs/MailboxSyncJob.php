<?php

namespace Vorkfork\Apps\Mail\Jobs;

use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Illuminate\Pagination\LengthAwarePaginator;
use Vorkfork\Apps\Mail\IMAP\Exceptions\ImapErrorException;
use Vorkfork\Apps\Mail\IMAP\Folder;
use Vorkfork\Apps\Mail\IMAP\MailboxSynchronizer;
use Vorkfork\Apps\Mail\IMAP\MailboxSyncToken;
use Vorkfork\Apps\Mail\Models\Account;
use Vorkfork\Apps\Mail\Models\Mailbox;
use Vorkfork\Core\Exceptions\JobNotFoundException;
use Vorkfork\Core\Models\Job;
use const Vorkfork\Apps\Mail\IMAP\MESSAGES_PER_PAGE;

class MailboxSyncJob {
	protected Mailbox $mailbox;
	protected Account $account;
	protected MailboxSynchronizer $synchronizer;
	protected Folder $folder;
	protected ?Job $job = null;
	protected int $id;

	/**
	 * @param int $mailboxId
	 * @throws ImapErrorException
	 * @throws ORMException
	 * @throws OptimisticLockException
	 * @throws MissingMappingDriverImplementation
	 */
	public function __construct(int $mailboxId) {
		$this->id = $mailboxId;
		$this->execute();
	}

	public function register(): void {
		$this->mailbox = Mailbox::find($this->id);
		$this->account = $this->mailbox->getAccount();
		$this->synchronizer = MailboxSynchronizer::register($this->mailbox);
		$this->synchronizer->getMailbox()->ping();
		$this->folder = $this->synchronizer->getMailbox()->getFolderByPath($this->mailbox->getPath());
		$this->synchronizer->getMailbox()->setFolder($this->folder);
	}

	/**
	 * @return void
	 * @throws ORMException
	 * @throws OptimisticLockException
	 * @throws MissingMappingDriverImplementation
	 */
	public function execute(): void {
		$this->register();
		$mailboxSyncToken = new MailboxSyncToken();
		$token = $this->mailbox->getSyncToken();
		if(!is_null($token)){
			$mailboxSyncToken->fromJson($token);
		}

		$this->synchronizer->syncAllMessagesInFolder(
			$this->folder,
			$mailboxSyncToken->getPage(),
			MESSAGES_PER_PAGE,
			function(LengthAwarePaginator $paginator, Folder $folder) use ($mailboxSyncToken) {
				//todo try catch and store sync token and save job status running
				$this->mailbox = Mailbox::find($this->id);
				$currentPage = $paginator->currentPage();
				$mailboxSyncToken->setStart(new \DateTime());
				$mailboxSyncToken->setPage($currentPage);
				$mailboxSyncToken->setSuccess(true);
				$mailboxSyncToken->setFinish($currentPage === $paginator->lastPage());
				$this->mailbox->setSyncToken($mailboxSyncToken->toJson());
				$this->mailbox->em()->persist($this->mailbox);
			});
	}
}
