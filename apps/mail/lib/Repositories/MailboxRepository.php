<?php

namespace Vorkfork\Apps\Mail\Repositories;

use Vorkfork\Apps\Mail\DTO\MailboxDTO;
use Vorkfork\Core\Repositories\Repository;
use Vorkfork\Serializer\JsonSerializer;

class MailboxRepository extends Repository
{
	public function getUnusedMailboxesByNames(array $names)
	{
		return JsonSerializer::deserializeArrayStatic($this->notIn('name', $names)->results(), MailboxDTO::class);
	}
}