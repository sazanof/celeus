<?php

namespace Vorkfork\Apps\Mail\Jobs;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Illuminate\Pagination\LengthAwarePaginator;
use Vorkfork\Apps\Mail\IMAP\Folder;
use Vorkfork\Apps\Mail\IMAP\MailboxSynchronizer;
use Vorkfork\Apps\Mail\IMAP\MailboxSyncToken;
use Vorkfork\Apps\Mail\Models\Account;
use Vorkfork\Apps\Mail\Models\Mailbox;
use Vorkfork\Core\Exceptions\JobNotFoundException;
use Vorkfork\Core\Models\Job;
use const Vorkfork\Apps\Mail\IMAP\MESSAGES_PER_PAGE;

class MailboxSyncJob
{
	protected Mailbox $mailbox;
	protected Account $account;
	protected MailboxSynchronizer $synchronizer;
	protected Folder $folder;
	protected ?Job $job = null;
	protected int $id;

	/**
	 * @param int $mailboxId
	 * @throws ORMException
	 * @throws OptimisticLockException
	 * @throws TransactionRequiredException|JobNotFoundException
	 */
	public function __construct(int $mailboxId)
	{
		$this->id = $mailboxId;
		$this->job = Job::repository()->findJobByClass(self::class, ['mailboxId' => $mailboxId]);

		if (!is_null($this->job)) {
			// if job exists in db, continue
			try {
				$this->register();
				$this->execute();
				$this->job->setStatus(\Vorkfork\Core\Jobs\Job::STATUS_FINISHED);
				$this->job->save();
			} catch (\TypeError|\Exception $exception) {
				// update JOB failed, mailbox is null
				dump($exception);
				$this->job->setStatus(\Vorkfork\Core\Jobs\Job::STATUS_FAILED);
				$this->job->save();
			}
		} else {
			throw new JobNotFoundException();
		}

	}

	public function register(): void
	{
		$this->mailbox = Mailbox::find($this->id);
		$this->account = $this->mailbox->getAccount();
		$this->synchronizer = MailboxSynchronizer::register($this->account);
		$this->synchronizer->getMailbox()->ping();
		$this->folder = $this->synchronizer->getMailbox()->getFolderByPath($this->mailbox->getPath());
		$this->synchronizer->getMailbox()->setFolder($this->folder);
	}

	/**
	 * @return void
	 * @throws ORMException
	 * @throws OptimisticLockException
	 * @throws \Doctrine\ORM\Exception\MissingMappingDriverImplementation
	 * @throws \Vorkfork\Apps\Mail\IMAP\Exceptions\ImapErrorException
	 */
	public function execute(): void
	{
		$mailboxSyncToken = new MailboxSyncToken();
		$token = $this->mailbox->getSyncToken();
		if (!is_null($token)) {
			$mailboxSyncToken->fromJson($token);
		}

		$this->synchronizer->syncAllMessagesInFolder(
			$this->folder,
			$mailboxSyncToken->getPage(),
			MESSAGES_PER_PAGE,
			function (LengthAwarePaginator $paginator, Folder $folder) use ($mailboxSyncToken) {
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
