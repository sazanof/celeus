<?php

namespace Vorkfork\Serializer;

interface ISerializer
{
    public function serialize(mixed $data);

    public static function serializeStatic(mixed $data);
}