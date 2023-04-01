<?php

namespace Vorkfork\Apps\Settings\Repositories;

use Vorkfork\Core\Models\Permissions;
use Vorkfork\Core\Repositories\PermissionsRepository;
use Vorkfork\Security\Acl;

class SettingsRepository extends PermissionsRepository
{
	const SETTINGS_TYPES = [
		'settings.profile',
		'settings.core'
	];

	public static function insertDefaultSettingsPermissions()
	{
		foreach (self::SETTINGS_TYPES as $type) {
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