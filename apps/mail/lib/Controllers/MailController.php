<?php

namespace Vorkfork\Apps\Mail\Controllers;

use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
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
use Vorkfork\Core\Controllers\Controller;
use Vorkfork\Core\Exceptions\ErrorResponse;
use Vorkfork\Serializer\JsonSerializer;
use Webklex\PHPIMAP\Folder;

class MailController extends Controller
{

	protected ?MailboxSynchronizer $synchronizer = null;

	/**
	 * Load user accounts list as Dto collection
	 * @return mixed
	 */
	public function loadAccounts()
	{
		return AccountCollection::getUserAccounts($this->user->username);
	}

	/**
	 * Check and add user email account
	 * @throws AccountAlreadyExistsException
	 */
	public function addAccount(Request $request)
	{
		if (!$request->headers->get(X_AJAX_CALL)) {
			die;
		}
		$ar = $request->toArray();
		$imap = $ar['imap'];
		$smtp = $ar['smtp'];

		try {
			// Checking if account already exists in user's account list
			$existing = Account::repository()->getUserAccountByEmail($imap['user'], $this->user->username);
			if (count($existing) > 0) {
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
				), $imap['user'], $imap['password'], OP_HALFOPEN);
			if ($connection->check()) {
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

		} catch (TransportException $e) {
			return new ErrorResponse($e->getMessage());
		}
		return false;
	}

	public function saveAccount(int $id, Request $request)
	{
		$account = Account::find($id);
		if (!is_null($account) && AccountAcl::belongsToAuthenticatedUser($account)) {
			return $account->update($request->toArray())->toDto(AccountDto::class);
		}
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
	 * @throws \Webklex\PHPIMAP\Exceptions\AuthFailedException
	 * @throws \Webklex\PHPIMAP\Exceptions\ConnectionFailedException
	 * @throws \Webklex\PHPIMAP\Exceptions\FolderFetchingException
	 * @throws \Webklex\PHPIMAP\Exceptions\ImapBadRequestException
	 * @throws \Webklex\PHPIMAP\Exceptions\ImapServerErrorException
	 * @throws \Webklex\PHPIMAP\Exceptions\ResponseException
	 * @throws \Webklex\PHPIMAP\Exceptions\RuntimeException
	 */
	public function syncMailboxes(int $id): mixed
	{

		dd(
			JsonSerializer::deserializeArrayStatic(
				Account::find($id)->getMailboxes(function (\Vorkfork\Apps\Mail\Models\Mailbox $mailbox) {
					return $mailbox->getParent() === null;
				}),
				MailboxDTO::class
			)
		);
		$this->synchronizer = MailboxSynchronizer::register(Account::find($id));
		$this->synchronizer->getAllFolders(function (Folder $imapFolder, $index) {
			$this->synchronizer->syncFolder($imapFolder, $index);
		}, true);
		//dd(Account::find($id)->getMailboxes())
		return JsonSerializer::deserializeArrayStatic(
			Account::find($id)->getMailboxes(function (\Vorkfork\Apps\Mail\Models\Mailbox $mailbox) {
				return $mailbox->getParent() === null;
			}),
			MailboxDTO::class
		);
	}

}
