<?php

namespace Celeus\Core\Repositories;

use Celeus\Core\Models\Permissions;
use Celeus\Security\Acl;

class PermissionsRepository extends CeleusRepository
{
    /**
     * @throws \Doctrine\Persistence\Mapping\MappingException
     */
    public function insertDefaultPermissions($type = 'core')
    {
        Permissions::insertBulk([
            [
                'type' => $type,
                'action' => Acl::CAN_CREATE
            ],
            [
                'type' => $type,
                'action' => Acl::CAN_READ
            ],
            [
                'type' => $type,
                'action' => Acl::CAN_UPDATE
            ],
            [
                'type' => $type,
                'action' => Acl::CAN_DELETE
            ]
        ]);
    }

    /**
     * @param $name
     * @return PermissionsRepository
     */
    public function whereNameLike($type): static
    {
        $this->_qb->where($this->as . '.type LIKE :type')->setParameter('type',$type);
        return $this;
    }
}