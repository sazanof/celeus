<?php

namespace Vorkfork\Apps\Mail\Repositories;

use Vorkfork\Apps\Mail\Models\Message;
use Vorkfork\Core\Repositories\Repository;

class MessageRepository extends Repository
{
	/**
	 * @param string $messageId
	 * @return Message|null
	 */
	public function findOneByMessageId(string $messageId): ?Message
	{
		return $this->findOneBy([
			'messageId' => $messageId
		]);
	}
}