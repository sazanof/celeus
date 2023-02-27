<?php
namespace Vorkfork\Apps\Mail\Models;
use Vorkfork\Database\Entity;
use Vorkfork\Database\Trait\Timestamps;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(columns: ['id'], name: 'id')]
#[ORM\Table(name: '`mail_messages`')]
#[ORM\HasLifecycleCallbacks]
class Messages extends Entity
{
    use Timestamps;
    #[ORM\Id]
    #[ORM\Column(type: Types::BIGINT)]
    #[ORM\GeneratedValue]
    private int $id;
}