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

	public function getMessages(int $page, int $limit) {
		dd($this->select()->limit($limit)->results(MessageDTO::class));
		return $this->select()->limit($limit)->results(MessageDTO::class);
	}
}
