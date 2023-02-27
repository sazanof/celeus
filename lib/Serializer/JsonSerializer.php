<?php

namespace Vorkfork\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonSerializer implements ISerializer
{
    private static ISerializer|null $instance = null;
    private ?Serializer $serializer = null;

    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function serialize(mixed $data): string
    {
        return $this->serializer->serialize($data, 'json');
    }

    public static function serializeStatic(mixed $data): string
    {
        if(!self::$instance instanceof ISerializer){
            self::$instance = new static();
        }
        return  self::$instance->serialize($data);
    }
}