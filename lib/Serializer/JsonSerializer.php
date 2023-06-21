<?php

namespace Vorkfork\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonSerializer implements ISerializer
{
	private static ISerializer|null $instance = null;
	private ?Serializer $serializer;

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

	public function deserialize(string $json, string $dtoClass, $format = 'json'): mixed
	{
		return $this->serializer->deserialize($json, $dtoClass, $format);
	}

	public function deserializeArray(string $json, string $dtoClass, $format = 'json'): mixed
	{
		return $this->serializer->deserialize($json, $dtoClass . '[]', $format);
	}

	public static function serializeStatic(mixed $data): string
	{
		if (!self::$instance instanceof ISerializer) {
			self::$instance = new static();
		}
		return self::$instance->serialize($data);
	}

	public static function deserializeStatic(mixed $json, string $dtoClass, string $format = 'json'): mixed
	{
		if (!self::$instance instanceof ISerializer) {
			self::$instance = new static();
		}
		if (is_array($json) || is_object($json)) {
			$json = self::$instance->serialize($json);
		}
		return self::$instance->deserialize($json, $dtoClass, $format);
	}

	public static function deserializeArrayStatic(mixed $data, string $dtoClass, $format = 'json'): mixed
	{
		if (is_array($data)) {
			$data = JsonSerializer::serializeStatic($data);
		}
		if (!self::$instance instanceof ISerializer) {
			self::$instance = new static();
		}
		return self::$instance->deserializeArray($data, $dtoClass, $format);
	}
}