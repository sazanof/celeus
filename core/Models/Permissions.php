<?php

namespace Celeus\Core\Models;

use Celeus\Core\Repositories\PermissionsRepository;
use Celeus\Database\Entity;
use Celeus\Database\Trait\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * @method static PermissionsRepository repository()
 */

#[ORM\Entity(repositoryClass: PermissionsRepository::class)]
#[ORM\Index(columns: ['type', 'action'], name: 'ata')]
#[ORM\Table(name: '`permissions`')]
class Permissions extends Entity
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\Column(type: Types::BIGINT)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $type;

    #[ORM\Column(type: Types::STRING)]
    private string $action;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }
}