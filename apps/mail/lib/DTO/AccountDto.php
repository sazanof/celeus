<?php

namespace Vorkfork\Apps\Mail\DTO;

use Sabre\VObject\Property\VCard\DateTime;
use Vorkfork\DTO\BaseDto;

class AccountDto extends BaseDto
{
	public string $user;

	public string $email;

	public string $name;

	public bool $isDefault;

	public ?DateTime $lastSync = null;
}