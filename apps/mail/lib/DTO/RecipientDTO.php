<?php

namespace Vorkfork\Apps\Mail\DTO;

use Vorkfork\DTO\BaseDto;

class RecipientDTO extends BaseDto {
	public ?string $name;
	public string $address;
	public int $id;
	public int $type;
}
