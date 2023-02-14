<?php

namespace Celeus\Apps\Mail\Repositories;

use Celeus\Core\Models\Permissions;
use Celeus\Core\Repositories\PermissionsRepository;
use Celeus\Security\Acl;
use Doctrine\Persistence\Mapping\MappingException;

class MailPermissions extends PermissionsRepository
{
    const PERMISSION_TYPES = [
        'mail.message',
        'mail.settings',
        'mail.search'
    ];

    /**
     * @throws MappingException
     */
    public static function insertDefaultMailPermissions()
    {
        foreach (self::PERMISSION_TYPES as $type) {
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
    }
}