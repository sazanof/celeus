<?php

namespace Vorkfork\Apps\Mail\ACL;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Vorkfork\Apps\Mail\Models\Account;
use Vorkfork\Apps\Mail\Models\Mailbox as MailboxModel;
use Vorkfork\Auth\Auth;
use Vorkfork\Core\Models\User;

class AccountAcl {

	/**
	 * @param Account $account
	 * @return bool
	 */
	public static function belongsToAuthenticatedUser(Account $account): bool {
		return $account->getUser() === Auth::user()->username;
	}

	/**
	 * @param MailboxModel $mailbox
	 * @return bool
	 */
	public static function mailboxBelongsToAuthenticatedUser(MailboxModel $mailbox): bool {
		$user = User::repository()->findByUsername($mailbox->getAccount()->getUser());
		if($user instanceof User){
			if(Auth::getLoginUserID() === $user->getId()){
				return true;
			}
		}
		return false;
	}
}
