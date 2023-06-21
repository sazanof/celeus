<?php

namespace Vorkfork\Apps\Mail\Models;

use Doctrine\DBAL\Types\Types;
use Vorkfork\Database\Entity;
use Vorkfork\Database\Trait\Timestamps;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(columns: ['id'], name: 'id')]
#[ORM\Index(columns: ['message_id'], name: 'message_id')]
#[ORM\Table(name: '`mail_recipients`')]
#[ORM\HasLifecycleCallbacks]
class Recipient extends Entity
{
	public const TYPE_FROM = 0;
	public const TYPE_TO = 1;
	public const TYPE_CC = 1;
	public const TYPE_BCC = 3;

	use Timestamps;

	#[ORM\Id]
	#[ORM\Column(type: Types::BIGINT)]
	#[ORM\GeneratedValue]
	private int $id;

	#[ORM\Column(name: 'message_id', type: Types::BIGINT)]
	#[ORM\GeneratedValue]
	private int $messageId;

	#[ORM\Column(type: Types::STRING)]
	#[ORM\GeneratedValue]
	private string $name;

	#[ORM\Column(type: Types::STRING)]
	#[ORM\GeneratedValue]
	private string $address;

	#[ORM\Column(type: Types::INTEGER, length: 1)]
	#[ORM\GeneratedValue]
	private int $type;

	#[ORM\ManyToOne(targetEntity: Message::class, inversedBy: 'recipients')]
	private Message $message;

	protected array $fillable = [
		'messageId',
		'name',
		'address',
		'type'
	];

	public function getMessage(): ?Message
	{
		return $this->message;
	}

	public function setMessage(?Message $category): self
	{
		$this->message = $category;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getMessageId(): int
	{
		return $this->messageId;
	}

	/**
	 * @param int $messageId
	 */
	public function setMessageId(int $messageId): void
	{
		$this->messageId = $messageId;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getAddress(): string
	{
		return $this->address;
	}

	/**
	 * @param string $address
	 */
	public function setAddress(string $address): void
	{
		$this->address = $address;
	}

	/**
	 * @return int
	 */
	public function getType(): int
	{
		return $this->type;
	}

	/**
	 * @param int $type
	 */
	public function setType(int $type): void
	{
		$this->type = $type;
	}

	public function setTypeByString(string $type)
	{
		$t = null;
		switch ($type) {
			case 'to':
				$t = self::TYPE_TO;
				break;
			case 'from':
				$t = self::TYPE_FROM;
				break;
			case 'cc':
				$t = self::TYPE_CC;
				break;
			case 'bcc':
				$t = self::TYPE_BCC;
				break;
		}
		if (!is_null($t)) {
			$this->setType($t);
		}
	}

}