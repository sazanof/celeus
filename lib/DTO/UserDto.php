<?php

namespace Vorkfork\DTO;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class UserDto extends BaseDto
{

	public int $id;

	public string $username;

	public string $email;

	public string $firstname;

	public string $lastname;

	public string $photo;

	public string $organization;

	public string $position;

	public string $language;

	public ?string $phone;

	public string $about;

	public array $groups;

	public static function setGroups()
	{
		return [1, 2];
	}

}