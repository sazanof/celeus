<?php

namespace Vorkfork\Apps\Mail\IMAP;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception\SyntaxErrorException;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Exception\ORMException;
use Sazanof\PhpImapSockets\Collections\AddressesCollection;
use Sazanof\PhpImapSockets\Exceptions\ConnectionException;
use Sazanof\PhpImapSockets\Exceptions\LoginFailedException;
use Sazanof\PhpImapSockets\Exceptions\NoResultsException;
use Sazanof\PhpImapSockets\Models\Address;
use Sazanof\PhpImapSockets\Models\Message;
use Sazanof\PhpImapSockets\Models\Paginator;
use Sazanof\PhpImapSockets\Parts\TextPart;
use Vorkfork\Apps\Mail\Exceptions\MailboxAlreadyExists;
use Vorkfork\Apps\Mail\IMAP\DTO\MailboxImapDTO;
use Vorkfork\Apps\Mail\Models\Mailbox as MailboxModel;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Vorkfork\Apps\Mail\Encryption\MailPassword;
use Vorkfork\Apps\Mail\IMAP\Exceptions\ImapErrorException;
use Vorkfork\Apps\Mail\Models\Account;
use Vorkfork\Apps\Mail\Models\Recipient;
use Vorkfork\Security\Str;
use Vorkfork\Apps\Mail\Models\Message as MessageModel;
use Sazanof\PhpImapSockets\Models\Mailbox as PISMailbox;

const MESSAGES_PER_PAGE = 50;

final class MailboxSynchronizer {
	protected ?Mailbox $mailbox = null;
	protected ?Server $server = null;
	protected ?Account $account = null;
	protected static ?MailboxSynchronizer $instance = null;
	protected PISMailbox $folder;
	protected string $syncToken;
	protected ?MailboxModel $mailboxModel = null;
	protected ArrayCollection $syncedFolders;
	protected ?Paginator $paginator;


	/**
	 * @param Account $account
	 * @param string $mailbox
	 * @throws EnvironmentIsBrokenException
	 * @throws WrongKeyOrModifiedCiphertextException
	 * @throws \ReflectionException
	 * @throws LoginFailedException
	 */
	public function __construct(
		Account $account,
		string  $mailbox = 'INBOX'
	) {
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
		$this->syncedFolders = new ArrayCollection();
		self::$instance = $this;
		return $this;
	}

	/**
	 * @return MailboxModel|null
	 */
	public function getMailboxModel(): ?MailboxModel {
		return $this->mailboxModel;
	}

	/**
	 * @param Account $account
	 * @param string $mailbox
	 * @param int $flags
	 * @param int $retries
	 * @return MailboxSynchronizer|null
	 * @throws EnvironmentIsBrokenException
	 * @throws LoginFailedException
	 * @throws WrongKeyOrModifiedCiphertextException
	 * @throws \ReflectionException
	 */
	public static function register(Account $account, string $mailbox = 'INBOX', int $flags = OP_HALFOPEN, int $retries = 3): ?MailboxSynchronizer {
		if(is_null(self::$instance)){
			return new self(account: $account,
				mailbox: $mailbox
			);
		}
		return self::$instance;
	}

	/**
	 * @return Mailbox|null
	 */
	public function getMailbox(): ?Mailbox {
		return $this->mailbox;
	}

	/**
	 * @return Account|null
	 */
	public function getAccount(): ?Account {
		return $this->account;
	}

	/**
	 * @param \Closure|null $closure
	 * @param string $startPath
	 * @return void
	 * @throws \ReflectionException
	 * @throws ConnectionException
	 */
	public function getAllFolders(\Closure $closure = null, string $startPath = ''): void {
		$imapFolders = $this->getMailbox()->getMailboxes($startPath);
		/** @var PISMailbox $imapFolder */
		$i = 0;
		foreach($imapFolders->items() as $imapFolder) {
			if(is_callable($closure)){
				$closure($imapFolder, $i);
			}
			$i++;
		}
	}

	/**
	 * @param PISMailbox $folder
	 * @return string
	 */
	public function getParentPathFromFolder(PISMailbox $folder): string {
		$path = $folder->getOriginalPath();
		$explode = explode($folder->getDelimiter(), $path);
		unset($explode[array_key_last($explode)]);
		return implode($folder->getDelimiter(), $explode);
	}

	/**
	 * TODO resync if uidvalidity changed
	 * @param PISMailbox $folder
	 * @param int $position
	 * @return void
	 * @throws \ReflectionException
	 */
	public function syncFolder(PISMailbox $folder, int $position = 0): void {
		/** @var MailboxModel $parent */
		$parent = null;
		$parentPath = $this->getParentPathFromFolder($folder);
		if(!empty($parentPath)){
			$parent = MailboxModel::repository()->findOneBy(
				[
					'account' => $this->account,
					'path' => $parentPath
				]);
		}
		$this->folder = $folder;
		$this->mailbox->ping();
		$path = $folder->getOriginalPath();
		//todo - attributes
		$data = [
			'attributes' => $folder->getAttributes()->toArray(),
			'name' => $folder->getName(),
			'path' => $path,
			'delimiter' => $folder->getDelimiter(),
			'total' => $folder->getExists(),
			'unseen' => $folder->getUnseen() ?? 0,
			'uidValidity' => $folder->getUidvalidity()
		];
		$data['position'] = $position;
		/** @var MailboxModel $mbox */
		$mbox = MailboxModel::repository()
			->findOneBy([
				'account' => $this->account,
				'path' => $path,
			]);
		try {
			if(!is_null($mbox)){
				$mbox->update($data, function(MailboxModel $mailbox) use ($parent) {
					$mailbox->setAccount($this->account);
					if($parent instanceof MailboxModel){
						$mailbox->setParent($parent);
						$parent->addChild($mailbox);
					}
				});
				$target = $mbox;
			} else{
				$target = MailboxModel
					::create($data, function(MailboxModel $mailbox) use ($parent) {
						$mailbox->setAccount($this->account);
						if($parent instanceof MailboxModel){
							$mailbox->setParent($parent);
							$parent->addChild($mailbox);
						}
					});
			}

			$this->syncedFolders->add($target->getPath());
		} catch(\Exception $exception) {
			if(!$exception instanceof MailboxAlreadyExists){
				dd($exception);
				// todo log
			}

		}
	}

	/**
	 * @return ArrayCollection
	 */
	public function getSyncedFolders(): ArrayCollection {
		return $this->syncedFolders;
	}

	/**
	 * @param $name
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function switchFolder($name) {
		$this->getMailbox()->getFolderByPath($name);
		return $this;
	}


	/**
	 * @param int $page
	 * @param int $total
	 * @param string $direction
	 * @return array
	 * @throws NoResultsException
	 * @throws \ReflectionException
	 */
	public function syncMessages(int $page = 1, int $total = MESSAGES_PER_PAGE, string $direction = 'DESC'): array {
		$databaseIds = [];
		$this->folder = $this->getMailbox()->getFolder();
		$this->mailboxModel = MailboxModel::repository()->findOneBy(
			[
				'account' => $this->account,
				'path' => $this->folder->getOriginalPath()
			]);
		$this->paginator = $this->mailbox->getMessagesByPage($total, $page, $direction);
		if($this->paginator->getTotal() > 0){
			foreach($this->paginator->messages() as $message) {
				try {
					//dump($message);
					$databaseIds[] = $this->addMessageFromImapToDb($message);
				} catch(MissingMappingDriverImplementation $e) {
				} catch(ORMException $e) {
					dd($e);
				}
			}
		}
		return $databaseIds;
	}

	/**
	 * Sync all messages in folder
	 * @param Folder $folder
	 * @param int $page
	 * @param int $total
	 * @return void
	 */
	public function syncAllMessagesInFolder(Folder $folder, int $page = 1, int $total = MESSAGES_PER_PAGE, \Closure $closure = null) {
		/** @var LengthAwarePaginator $paginator */
		$this->paginator = $this->mailbox->getMessagesByPage($total, $page);
		do {
			$this->syncMessages($folder, $page, $total);
			if(is_callable($closure)){
				$closure($this->paginator, $folder);
			}
			$page++;
		} while($this->paginator->lastPage() >= $page);
	}

	/**
	 * @param Folder $folder
	 * @return MailboxImapDTO|null
	 * @throws ImapErrorException
	 */
	public function sync(Folder $folder): ?MailboxImapDTO {
		$this->syncFolder($folder);
		$this->syncAllMessagesInFolder($folder);
		return null;
	}


	/**
	 * @param Message $message
	 * @return int
	 * @throws MissingMappingDriverImplementation
	 * @throws ORMException
	 */
	public function addMessageFromImapToDb(Message $message): ?int {

		$struct = $message->getBodyStructure();
		$textParts = $struct->getTextParts();
		foreach($textParts as $textPart) {
			if($textPart->getMimeType() === 'text/plain'){
				dump($message->getBody($textPart));
			}
		}
		dd(123);
		$flags = new MessageFlags($message->getFlags());
		$to = $message->getTo();
		$from = $message->getFrom();
		$cc = $message->getCc();
		$bcc = $message->getBcc();
		$recipientsCompact = compact('to', 'from', 'cc', 'bcc');
		$flagged = $flags->isFlagged();
		$important = $message->isImportant();
		$answered = $flags->isAnswered();
		$deleted = $flags->isDeleted();
		$draft = $flags->isDraft();
		$spam = $flags->isSpam();
		$notSpam = $flags->isNotSpam();
		$recent = $flags->isRecent();
		$seen = $flags->isSeen();

		$messageId = $message->getMessageId();
		$subject = trim($message->getSubject());
		$bodyHtml = 'here is body';
		$preview = Str::truncate(trim('here is preview'), 200);
		$inReplyTo = $message->getInReplyTo();
		$chain = $message->getReferences();
		$sentAt = $message->getDate();
		$attachments = $message->isHasAttachments();
		$messageExisting = MessageModel::repository()->findOneByMessageId($messageId);

		if(is_null($messageExisting)){
			$dbMessage = new MessageModel();
			$dbMessage->setMailboxId($this->mailboxModel->getId()); // mailboxModel  must not be null!!!!!!!!!!!!!!
			$dbMessage->setMessageId($messageId);
			$dbMessage->setSubject($subject);
			$dbMessage->setBody($bodyHtml);
			$dbMessage->setInReplyTo($inReplyTo);
			$dbMessage->setChain($chain);
			$dbMessage->setPreview($preview);
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
			foreach($recipientsCompact as $type => $item) {
				if(!is_null($item)){
					if($item instanceof Address){
						$recipient = new Recipient();
						$recipient->setName($item->getName());
						$recipient->setAddress($item->getEmail());
						$recipient->setTypeByString($type);
						$dbMessage->addRecipient($recipient);
						$dbMessage->em()->persist($recipient);
					} elseif($item instanceof AddressesCollection){
						/** @var Address $address */
						foreach($item->items() as $address) {
							$recipient = new Recipient();
							$recipient->setName($address->getName());
							$recipient->setAddress($address->getEmail());
							$recipient->setTypeByString($type);
							$dbMessage->addRecipient($recipient);
							$dbMessage->em()->persist($recipient);
						}
					}
				}

			}

			try {
				$dbMessage->em()->persist($dbMessage);
				$dbMessage->em()->flush();
				return $dbMessage->getId();
			} catch(SyntaxErrorException $e) {
				dump($e->getQuery()->getSQL());
			} catch(ORMException $e) {
				dump($e->getMessage());
			} catch(\Exception $exception) {
				// TODO logging & fixing
				dump($exception);
				dump($subject);
				//dump($exception->getFile(), $exception->getLine(), $exception->getMessage());
			}
		} else{
			$messageExisting->setBody($bodyHtml);
			$messageExisting->setPreview($preview);
			$messageExisting->setMailboxId($this->mailboxModel->getId());
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
				//$messageExisting->em()->persist($messageExisting);
				$messageExisting->em()->flush();
				return $messageExisting->getId();
			} catch(\Exception|ORMException|\TypeError|SyntaxErrorException $e) {
				dump($e->getMessage());
			}
		}
		return null;
	}

	public function getDatabaseAccountMailboxes(): array {
		return MailboxModel::repository()->findBy(['accountId' => $this->account->getId()]);
	}

	public function deleteIfMailBoxNotExists() {
		// переписать на орм
		try {
			$this->account->removeUnusedMailboxes($this->getSyncedFolders());
		} catch(MissingMappingDriverImplementation|ORMException $e) {
			dd($e);
		}

		//$this->account->save();

	}
}
