<?php

namespace Vorkfork\DTO;

use Vorkfork\Core\Models\Group;

class UserDto extends BaseDto
{
    public int $id;

    public string $username;

    public string $email;

    public string $firstname;

    public string $lastname;

    public string $photo;

    //public array $groups;
}