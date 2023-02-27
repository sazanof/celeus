<?php

namespace Vorkfork\Database\Trait;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait Timestamps
{
    #[ORM\Column(
        name: 'created_at',
        type: Types::DATETIME_MUTABLE,
        nullable: false,
        options: ['default' => 'current_timestamp'],
        columnDefinition: "DATETIME default current_timestamp"
    )]
    private DateTime $createdAt;

    #[ORM\Column(
        name: 'updated_at',
        type: Types::DATETIME_MUTABLE,
        nullable: false,
        columnDefinition: "DATETIME on update current_timestamp"
    )]
    private DateTime $updatedAt;

    #[ORM\PrePersist]
    public function prePersistSetCreatedAt(): void
    {
        $this->setCreatedAt();
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

}