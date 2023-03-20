<?php

namespace Vorkfork\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonSerializer implements ISerializer
{
    private static ISerializer|null $instance = null;
    private ?Serializer $serializer = null;

    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(), new ArrayDenormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function serialize(mixed $data, $format = 'json'): string
    {
        return $this->serializer->serialize($data, $format);
    }

    public function deserialize(string $json, string $class, $format = 'json'): mixed
    {
        return $this->serializer->deserialize($json, $class, $format);
    }

    public function deserializeArray(string $json, string $class, $format = 'json'): mixed
    {
        return $this->serializer->deserialize($json, $class . '[]', $format);
    }

    public static function serializeStatic(mixed $data): string
    {
        if (!self::$instance instanceof ISerializer) {
            self::$instance = new static();
        }
        return self::$instance->serialize($data);
    }

    public static function deserializeStatic(string $json, string $class, string $format = 'json'): mixed
    {
        if (!self::$instance instanceof ISerializer) {
            self::$instance = new static();
        }
        return self::$instance->deserialize($json, $class, $format);
    }

    public static function deserializeArrayStatic(mixed $data, string $class, $format = 'json'): mixed
    {
        if (is_array($data)) {
            $data = JsonSerializer::serializeStatic($data);
        }
        if (!self::$instance instanceof ISerializer) {
            self::$instance = new static();
        }
        return self::$instance->deserializeArray($data, $class, $format);
    }
}