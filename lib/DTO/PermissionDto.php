<?php

namespace Vorkfork\DTO;

class PermissionDto extends BaseDto
{
	public int $id;

	public string $type;

	public string $action;
}