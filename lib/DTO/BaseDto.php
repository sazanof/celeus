<?php

namespace Vorkfork\DTO;

class BaseDto
{
    public function set(string $key, mixed $value): void
    {
        $this->$key = $value;
    }

    public function has(string $name): void
    {

    }

    public function get(string $key)
    {
        return $this->$key;
    }
}