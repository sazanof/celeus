<?php

namespace Vorkfork\Apps\Mail\Commands;

use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vorkfork\Apps\Mail\IMAP\Exceptions\ImapErrorException;
use Vorkfork\Apps\Mail\IMAP\MailboxSynchronizer;
use Vorkfork\Apps\Mail\Models\Account;
use Vorkfork\Apps\Mail\Models\Mailbox;

#[AsCommand(
	name: 'mail:account:sync',
	description: 'Sync user account',
	hidden: false
)]
class AccountSyncCommand extends Command
{
	protected ?int $id = null;
	protected ?int $mailboxId = null;

	protected function configure()
	{
		$this->setDefinition(
			new InputDefinition([
				new InputArgument('id', InputArgument::REQUIRED, 'User\'s account ID'),
				new InputArgument('mbox', InputArgument::OPTIONAL, 'User\'s mailbox ID'),
			])
		);;
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int
	 * @throws EnvironmentIsBrokenException
	 * @throws ImapErrorException
	 * @throws WrongKeyOrModifiedCiphertextException
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$this->id = $input->getArgument('id');
		$this->mailboxId = $input->getArgument('mbox');
		/** @var Account $account */
		$account = Account::repository()->find($this->id);
		$synchronizer = MailboxSynchronizer::register($account);
		$names = [];
		try {
			if ($this->mailboxId > 0) {
				$mbox = Mailbox::find($this->mailboxId);
				if (!is_null($mbox)) {
					$f = $synchronizer->getMailbox()->getFolderByPath($mbox->getName());
					$output->writeln('Trying to sync only one MBOX: ' . $mbox->getName());
					$synchronizer->sync($f);
				}
			} else {
				$synchronizer->getAllFolders(function (Folder $imapFolder) use ($synchronizer) {
					$synchronizer->sync($imapFolder);
				});
			}
		} catch (\Exception $exception) {
			// TODO log
		}
		return self::SUCCESS;
	}
}
