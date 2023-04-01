<?php

namespace Vorkfork\Security;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vorkfork\Core\Models\Group;
use Vorkfork\DTO\PermissionDto;
use Vorkfork\Serializer\JsonSerializer;

class Acl
{
	const CAN_CREATE = 'c';
	const CAN_READ = 'r';
	const CAN_UPDATE = 'u';
	const CAN_DELETE = 'd';

	public static function can()
	{

	}

	/**
	 * Get permissions array
	 * @param ArrayCollection|Collection $collection
	 * @return array
	 */
	public static function fromGroupsCollection(ArrayCollection|Collection $collection): array
	{
		$permissions = [];
		$res = array_map(function (Group $group) {
			return JsonSerializer::deserializeArrayStatic(
				$group->getPermissions()->toArray(),
				PermissionDto::class
			);
		}, $collection->toArray());
		foreach ($res as $perms) {
			foreach ($perms as $perm) {
				/* @var PermissionDto $perm */
				if (!isset($permissions[$perm->type]) || !in_array($perm->action, $permissions[$perm->type])) {
					$permissions[$perm->type][] = $perm->action;
				}
			}
		}
		return $permissions;
	}
}