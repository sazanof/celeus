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
        Permissions::create([
            'type' => $type,
            'action' => Acl::CAN_CREATE
        ]);

        Permissions::create([
            'type' => $type,
            'action' => Acl::CAN_READ
        ]);

        Permissions::create([
            'type' => $type,
            'action' => Acl::CAN_UPDATE
        ]);

        Permissions::create([
            'type' => $type,
            'action' => Acl::CAN_DELETE
        ]);
    }
}