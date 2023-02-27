<?php

namespace Vorkfork\Apps\Mail\Repositories;

use Vorkfork\Core\Models\Permissions;
use Vorkfork\Core\Repositories\PermissionsRepository;
use Vorkfork\Security\Acl;
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