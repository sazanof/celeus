<?php

namespace Vorkfork\Core\Models;

use Vorkfork\Database\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Vorkfork\Database\Trait\Timestamps;

#[ORM\Entity]
#[ORM\Index(columns: ['id'], name: 'group_id')]
#[ORM\Table(name: '`groups`')]
#[ORM\HasLifecycleCallbacks]
class Group extends Entity
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\Column(type: Types::BIGINT)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::STRING, unique: true)]
    public string $name;

    protected array $fillable = [
        'name'
    ];

    #[ORM\JoinTable(name: 'groups_permissions')]
    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'permission_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Permissions::class)]
    private Collection|array $permissions;

    public function getId(){
        return $this->id;
    }

    /**
     * @return Collection|array
     */
    public function getPermissions(): Collection|array
    {
        return $this->permissions;
    }

    /**
     * Set permissions
     * @param Collection|array $permissions
     */
    public function setPermissions(Collection|array $permissions): void
    {
        $this->permissions = new ArrayCollection((array)$permissions);
    }

    /**
     * Add permission to group permissions
     * @return void
     */
    public function addPermission(){

    }

    /**
     * Add permissions to group
     * @param Collection|array $permissions
     */
    public function addPermissions(Collection|array $permissions): void
    {
        $this->permissions = new ArrayCollection(array_merge((array)$permissions, $this->getPermissions()->toArray()));
    }

}