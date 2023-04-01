<?php

namespace Vorkfork\Core\Repositories;

use Vorkfork\Core\Models\Permissions;
use Vorkfork\Security\Acl;

class PermissionsRepository extends Repository
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
	 * @param $type
	 * @return PermissionsRepository
	 */
	public function whereNameLike($type): static
	{
		$this->_qb->where($this->as . '.type LIKE :type')->setParameter('type', $type);
		return $this;
	}
}