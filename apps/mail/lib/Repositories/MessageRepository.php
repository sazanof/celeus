<?php

namespace Vorkfork\Apps\Mail\Repositories;

use Vorkfork\Apps\Mail\DTO\MessageDTO;
use Vorkfork\Apps\Mail\Models\Message;
use Vorkfork\Core\Repositories\Repository;

class MessageRepository extends Repository {
	/**
	 * @param string $messageId
	 * @return Message|null
	 */
	public function findOneByMessageId(string $messageId): ?Message {
		return $this->findOneBy([
			'messageId' => $messageId
		]);
	}

	public function getMessages(int $mailboxId, int $page, int $limit) {
		return $this->select()->where('mailboxId', '=', $mailboxId)->limit($limit)->orderBy('sentAt', "DESC")->results(MessageDTO::class);
	}
}
