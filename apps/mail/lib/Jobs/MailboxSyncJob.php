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

	/**
	 * @param int $mailboxId
	 * @throws ORMException
	 * @throws OptimisticLockException
	 * @throws TransactionRequiredException|JobNotFoundException
	 */
	public function __construct(int $mailboxId)
	{
		$this->job = Job::repository()->findJobByClass(self::class, ['mailboxId' => $mailboxId]);
		if (!is_null($this->job)) {
			// if job exists in db, continue
			try {
				$this->mailbox = Mailbox::find($mailboxId);
				$this->register();
				$this->execute();
				$this->job->setStatus(\Vorkfork\Core\Jobs\Job::STATUS_FINISHED);
			} catch (\TypeError $exception) {
				// update JOB failed, mailbox is null
				dump($exception);
				$this->job->setStatus(\Vorkfork\Core\Jobs\Job::STATUS_FAILED);
			} finally {

				$this->job->save();
			}
		} else {
			throw new JobNotFoundException();
		}

	}

	public function register(): void
	{
		$this->account = $this->mailbox->getAccount();
		$this->synchronizer = MailboxSynchronizer::register($this->account);
		$this->folder = $this->synchronizer->getMailbox()->getFolderByPath($this->mailbox->getPath());
		$this->synchronizer->getMailbox()->setFolder($this->folder);
	}

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
			function (LengthAwarePaginator $paginator) use ($mailboxSyncToken) {
				//todo try catch and store sync token and save job status running
				$currentPage = $paginator->currentPage();
				$mailboxSyncToken->setStart(new \DateTime());
				$mailboxSyncToken->setPage($currentPage);
				$mailboxSyncToken->setSuccess(true);
				$mailboxSyncToken->setFinish($currentPage === $paginator->lastPage());
				$this->mailbox->setSyncToken($mailboxSyncToken->toJson());
				$this->mailbox->save();
			});
	}
}
