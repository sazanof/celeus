<?php

namespace Vorkfork\Apps\Mail\Controllers;

use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Sazanof\PhpImapSockets\Exceptions\ConnectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportException;
use Vorkfork\Apps\Mail\ACL\AccountAcl;
use Vorkfork\Apps\Mail\Collections\AccountCollection;
use Vorkfork\Apps\Mail\DTO\AccountDto;
use Vorkfork\Apps\Mail\DTO\MailboxDTO;
use Vorkfork\Apps\Mail\Encryption\MailPassword;
use Vorkfork\Apps\Mail\Exceptions\AccountAlreadyExistsException;
use Vorkfork\Apps\Mail\IMAP\Exceptions\ImapErrorException;
use Vorkfork\Apps\Mail\IMAP\Mailbox;
use Vorkfork\Apps\Mail\IMAP\MailboxSynchronizer;
use Vorkfork\Apps\Mail\IMAP\Server;
use Vorkfork\Apps\Mail\Models\Account;
use Vorkfork\Apps\Mail\SMTP\Smtp;
use Vorkfork\Apps\Mail\SMTP\SmtpConfig;
use Vorkfork\Auth\Auth;
use Vorkfork\Core\Controllers\Controller;
use Vorkfork\Core\Exceptions\ErrorResponse;
use Vorkfork\Core\Models\User;
use Vorkfork\DTO\BaseDto;
use Vorkfork\Serializer\JsonSerializer;
use Vorkfork\Apps\Mail\Models\Mailbox as MailboxModel;

class MailController extends Controller {

	protected ?MailboxSynchronizer $synchronizer = null;

	/**
	 * Load user accounts list as Dto collection
	 * @return AccountDto[]
	 */
	public function loadAccounts(): array {
		return AccountCollection::getUserAccounts($this->user->username);
	}

	/**
	 * Check and add user email account
	 * @param Request $request
	 * @return array|false|void|ErrorResponse
	 * @throws EnvironmentIsBrokenException
	 * @throws ImapErrorException
	 * @throws ORMException
	 * @throws OptimisticLockException
	 * @throws TransactionRequiredException
	 * @throws \Doctrine\Persistence\Mapping\MappingException
	 */
	public function addAccount(Request $request) {
		if(!$request->headers->get(X_AJAX_CALL)){
			die;
		}
		$ar = $request->toArray();
		$imap = $ar['imap'];
		$smtp = $ar['smtp'];

		try {
			// Checking if account already exists in user's account list
			$existing = Account::repository()->getUserAccountByEmail($imap['user'], $this->user->username);
			if(count($existing) > 0){
				return new ErrorResponse((new AccountAlreadyExistsException())->getMessage(), 409);
			}
			// Testing smtp connection
			Smtp::test(new SmtpConfig(
				host: $smtp['host'],
				port: $smtp['port'],
				username: $smtp['user'],
				password: $smtp['password'],
				tls: $smtp['encryption'] !== 'none'
			));
			// Testing imap connection
			$connection = new Mailbox(
				new Server(
					host: $imap['host'],
					port: $imap['port'],
					encryption: $imap['encryption'],
					validateCert: true // TODO move to account creating
				), $imap['user'], $imap['password'], OP_HALFOPEN);
			if($connection->isConnected()){
				$account = Account::create([
					'user' => $this->user->username,
					'email' => $imap['user'],
					'name' => $this->user->getUserManager()->getFullname(),
					'smtpUser' => $smtp['user'],
					'smtpPassword' => MailPassword::encrypt($smtp['password']),
					'smtpServer' => $smtp['host'],
					'smtpPort' => $smtp['port'],
					'smtpEncryption' => $smtp['encryption'],
					'imapUser' => $imap['user'],
					'imapPassword' => MailPassword::encrypt($imap['password']),
					'imapServer' => $imap['host'],
					'imapPort' => $imap['port'],
					'imapEncryption' => $imap['encryption'],
					'isDefault' => Account::repository()->count([]) === 0
				]);
				return [
					'success' => true,
					'account' => $account->toDto(AccountDto::class)
				];
			}

		} catch(TransportException $e) {
			return new ErrorResponse($e->getMessage());
		}
		return false;
	}

	/**
	 * @param int $id
	 * @param Request $request
	 * @return BaseDto|null
	 * @throws ORMException
	 * @throws OptimisticLockException
	 * @throws TransactionRequiredException
	 * @throws MissingMappingDriverImplementation
	 */
	public function saveAccount(int $id, Request $request) {
		$account = Account::find($id);
		if(!is_null($account) && AccountAcl::belongsToAuthenticatedUser($account)){
			return $account->update($request->toArray())->toDto(AccountDto::class);
		}
		return null;
	}

	/**
	 * @param int $id
	 * @return mixed
	 * @throws EnvironmentIsBrokenException
	 * @throws ImapErrorException
	 * @throws ORMException
	 * @throws OptimisticLockException
	 * @throws TransactionRequiredException
	 * @throws WrongKeyOrModifiedCiphertextException
	 * @throws ConnectionException
	 */
	public function syncMailboxes(int $id): mixed {
		$this->synchronizer = MailboxSynchronizer::register(
			Account::find($id)
		);
		try {
			$this->synchronizer->getAllFolders(function(\Sazanof\PhpImapSockets\Models\Mailbox $imapFolder, $index) {
				$this->synchronizer->syncFolder($imapFolder, $index);
			}, false);
			$this->synchronizer->deleteIfMailBoxNotExists();
		} catch(\ReflectionException $e) {
		}

		return JsonSerializer::deserializeArrayStatic(
			Account::find($id)->getMailboxes(),
			MailboxDTO::class
		);
	}

	public function syncMailbox(int $id) {
		$mailbox = MailboxModel::find($id);
		$user = User::repository()->findByUsername($mailbox->getAccount()->getUser());
		if($mailbox instanceof MailboxModel && $user instanceof User){
			if(Auth::getLoginUserID() === $user->getId()){
				$this->synchronizer = MailboxSynchronizer::register(
					$mailbox->getAccount()
				);
				dd($this->synchronizer->getMailbox());
			}
		}


	}

}
