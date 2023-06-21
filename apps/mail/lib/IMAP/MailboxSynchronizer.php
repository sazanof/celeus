<?php

namespace Vorkfork\Apps\Mail\IMAP;

use Doctrine\DBAL\Exception\SyntaxErrorException;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Exception\ORMException;
use Illuminate\Pagination\LengthAwarePaginator;
use Vorkfork\Apps\Mail\IMAP\DTO\MailboxImapDTO;
use Vorkfork\Apps\Mail\Models\Mailbox as MailboxModel;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Vorkfork\Apps\Mail\Encryption\MailPassword;
use Vorkfork\Apps\Mail\IMAP\Exceptions\ImapErrorException;
use Vorkfork\Apps\Mail\Models\Account;
use Vorkfork\Apps\Mail\Models\Recipient;
use Webklex\PHPIMAP\Address;
use Webklex\PHPIMAP\Attribute;
use Webklex\PHPIMAP\Folder;
use Webklex\PHPIMAP\Message;
use Vorkfork\Apps\Mail\Models\Message as MessageModel;

const MESSAGES_PER_PAGE = 50;

final class MailboxSynchronizer
{
	protected ?Mailbox $mailbox = null;
	protected ?Server $server = null;
	protected ?Account $account = null;
	protected static ?MailboxSynchronizer $instance = null;
	protected Folder $folder;
	protected MailboxImapDTO $mailboxDTO;


	/**
	 * @param Account $account
	 * @param string $mailbox
	 * @param int $flags
	 * @param int $retries
	 * @param array $options
	 * @throws EnvironmentIsBrokenException
	 * @throws ImapErrorException
	 * @throws WrongKeyOrModifiedCiphertextException
	 */
	public function __construct(
		Account $account,
		string  $mailbox = 'INBOX'
	)
	{
		$this->server = new Server(
			host: $account->getImapServer(),
			port: $account->getImapPort(),
			mailbox: $mailbox,
			encryption: $account->getImapEncryption(),
			validateCert: true // TODO move to account creating
		);
		$this->mailbox = new Mailbox(
			server: $this->server,
			username: $account->getImapUser(),
			password: MailPassword::decrypt($account->getImapPassword()),
		);
		$this->account = $account;
		self::$instance = $this;
		return $this;
	}

	/**
	 * @param Account $account
	 * @param string $mailbox
	 * @param int $flags
	 * @param int $retries
	 * @return MailboxSynchronizer|null
	 * @throws EnvironmentIsBrokenException
	 * @throws ImapErrorException
	 * @throws WrongKeyOrModifiedCiphertextException
	 */
	public static function register(Account $account, string $mailbox = 'INBOX', int $flags = OP_HALFOPEN, int $retries = 3): ?MailboxSynchronizer
	{
		if (is_null(self::$instance)) {
			return new self(account: $account,
				mailbox: $mailbox
			);
		}
		return self::$instance;
	}

	/**
	 * @return Mailbox|null
	 */
	public function getMailbox(): ?Mailbox
	{
		return $this->mailbox;
	}

	/**
	 * @return Account|null
	 */
	public function getAccount(): ?Account
	{
		return $this->account;
	}

	/**
	 * @param Folder $folder
	 * @return void
	 * @throws ImapErrorException
	 */
	public function syncFolder(Folder $folder)
	{
		$this->folder = $folder;
		$this->mailbox->ping();
		$data = [
			'accountId' => $this->account->getId(),
			'name' => $folder->full_name,
			'delimiter' => $folder->delimiter,
			'total' => $folder->status['exists'],
			'unseen' => $folder->status['unseen'] ?? 0,
			'uidValidity' => $folder->status['uidvalidity'],
			'lastSync' => new \DateTime()
		];
		/** @var MailboxModel $mbox */
		$mbox = MailboxModel::repository()
			->findOneBy([
				'accountId' => $this->account->getId(),
				'name' => $folder->full_name
			]);
		try {
			if (!is_null($mbox)) {
				$this->mailboxDTO = $mbox->update($data)->toDto(MailboxImapDTO::class);
			} else {
				$this->mailboxDTO = MailboxModel::create($data)->toDto(MailboxImapDTO::class);
			}
		} catch (\Exception $exception) {
			// todo log
		}
	}

	/**
	 * @param Folder $folder
	 * @param int $page
	 * @param int $total
	 * @return void
	 */
	public function syncMessages(Folder $folder, int $page, int $total = MESSAGES_PER_PAGE)
	{
		$this->mailbox->setFolder($folder);
		$paginator = $this->mailbox->getMessagesByPage($total, $page);
		if ($paginator->count() > 0) {
			/** @var Message $message */
			echo 'Load page ' . $page . ' from ' . $paginator->lastPage() . PHP_EOL;
			foreach ($paginator as $message) {
				try {
					$this->addMessageFromImapToDb($message);
				} catch (MissingMappingDriverImplementation $e) {
				} catch (ORMException $e) {
					dd($e);
				}
			}
		}
	}

	/**
	 * @param Folder $folder
	 * @return MailboxImapDTO|null
	 * @throws ImapErrorException
	 */
	public function sync(Folder $folder): ?MailboxImapDTO
	{
		$this->syncFolder($folder);
		$page = 1;
		/** @var LengthAwarePaginator $paginator */
		do {
			$this->syncMessages($folder, $page);
			$page++;
		} while ($paginator->lastPage() >= $page);
		return null;

	}


	/**
	 * @throws MissingMappingDriverImplementation
	 * @throws ORMException
	 */
	public function addMessageFromImapToDb(Message $message): void
	{
		$flags = $message->flags;

		$to = $message->getTo();
		$from = $message->getFrom();
		$cc = $message->getCc();
		$bcc = $message->getBcc();
		$recipientsCompact = compact('to', 'from', 'cc', 'bcc');
		$flagged = MessageFlags::isFlagged($flags);
		$important = MessageFlags::isImportant($flags);
		$answered = MessageFlags::isAnswered($flags);
		$deleted = MessageFlags::isDeleted($flags);
		$draft = MessageFlags::isDraft($flags);
		$spam = MessageFlags::isSpam($flags);
		$notSpam = MessageFlags::isNotSpam($flags);
		$recent = MessageFlags::isRecent($flags);
		$seen = MessageFlags::isSeen($flags);

		$messageId = $message->getMessageId()->first();
		$subject = $message->getSubject()->first();
		$bodyHtml = $message->getHTMLBody();
		//$preview = '';  todo = check for better
		$inReplyTo = $message->getInReplyTo()->first();
		$chain = $message->getReferences();
		$sentAt = $message->date;
		$attachments = $message->hasAttachments();

		$messageExisting = MessageModel::repository()->findOneByMessageId($messageId);

		if (is_null($messageExisting)) {
			$dbMessage = new MessageModel();
			$dbMessage->setMailboxId($this->mailboxDTO->id);
			$dbMessage->setMessageId($messageId);
			$dbMessage->setSubject($subject);
			$dbMessage->setBody($bodyHtml);
			$dbMessage->setInReplyTo($inReplyTo);
			$dbMessage->setChain($chain);
			//$dbMessage->setPreview($preview);
			$dbMessage->setSentAt($sentAt);
			$dbMessage->setAnswered($answered);
			$dbMessage->setAttachments($attachments);
			$dbMessage->setImportant($important);
			$dbMessage->setRecent($recent);
			$dbMessage->setSeen($seen);
			$dbMessage->setDraft($draft);
			$dbMessage->setFlagged($flagged);
			$dbMessage->setSpam($spam);
			$dbMessage->setNotSpam($notSpam);
			$dbMessage->setDeleted($deleted);
			$dbMessage->setLocalMessage(false);

			/** @var Attribute $item */
			foreach ($recipientsCompact as $type => $item) {
				/** @var Address $address */
				foreach ($item->all() as $address) {
					$recipient = new Recipient();
					$recipient->setName($address->personal);
					$recipient->setAddress($address->mail);
					$recipient->setTypeByString($type);
					$dbMessage->addRecipient($recipient);
					$dbMessage->em()->persist($recipient);
				}
			}
			try {
				$dbMessage->em()->persist($dbMessage);
				$dbMessage->em()->flush();
			} catch (SyntaxErrorException $e) {
				dump($e->getQuery()->getSQL());
			} catch (ORMException $e) {
				dump($e->getMessage());
			} catch (\Exception $exception) {
				dump($subject);
				//dump($exception->getFile(), $exception->getLine(), $exception->getMessage());
			}
		} else {
			$messageExisting->setBody($bodyHtml);
			//$dbMessage->setPreview($preview);
			$messageExisting->setMailboxId($this->mailboxDTO->id);
			$messageExisting->setMessageId($messageId);
			$messageExisting->setSubject($subject);
			$messageExisting->setInReplyTo($inReplyTo);
			$messageExisting->setChain($chain);
			$messageExisting->setAnswered($answered);
			$messageExisting->setImportant($important);
			$messageExisting->setRecent($recent);
			$messageExisting->setSeen($seen);
			$messageExisting->setDraft($draft);
			$messageExisting->setFlagged($flagged);
			$messageExisting->setSpam($spam);
			$messageExisting->setNotSpam($notSpam);
			$messageExisting->setDeleted($deleted);
			$messageExisting->setAttachments($attachments);

			try {
				$messageExisting->em()->persist($messageExisting);
				$messageExisting->em()->flush();
			} catch (\Exception $e) {
				dump($e->getMessage());
			}
		}
	}

	public function getDatabaseAccountMailboxes(): array
	{
		return MailboxModel::repository()->findBy(['accountId' => $this->account->getId()]);
	}

	public function deleteIfMailBoxNotExists(array $names)
	{
		return MailboxModel::repository()
			->delete()
			->notIn('name', $names)
			->where(
				'accountId', '=', $this->account->getId()
			)
			->results();
	}
}
