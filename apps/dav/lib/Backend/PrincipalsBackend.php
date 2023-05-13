<?php

namespace Vorkfork\Apps\Dav\Backend;
class PrincipalsBackend implements \Sabre\DAVACL\PrincipalBackend\BackendInterface
{

	public function getPrincipalsByPrefix($prefixPath)
	{
		return [
			[
				'id' => 1,
				'uri' => 'principals/admin',
				'{DAV:}displayname' => 'User 1',
				'{http://sabredav.org/ns}email-address' => 'admin'
			]
		];
		// TODO: Implement getPrincipalsByPrefix() method.
	}

	public function getPrincipalByPath($path)
	{
		return [
			'id' => 1,
			'uri' => 'principals/admin',
			'{DAV:}displayname' => 'User 1',
			'{http://sabredav.org/ns}email-address' => 'admin'
		];
		// TODO: Implement getPrincipalByPath() method.
	}

	public function updatePrincipal($path, \Sabre\DAV\PropPatch $propPatch)
	{
		// TODO: Implement updatePrincipal() method.
	}

	public function searchPrincipals($prefixPath, array $searchProperties, $test = 'allof')
	{
		return [
			[
				'id' => 1,
				'uri' => 'principals/admin',
				'{DAV:}displayname' => 'User 1',
				'{http://sabredav.org/ns}email-address' => 'admin'
			]
		];
		// TODO: Implement searchPrincipals() method.
	}

	public function findByUri($uri, $principalPrefix)
	{

		return [
			'id' => 1,
			'uri' => 'principals/admin',
			'{DAV:}displayname' => 'User 1',
			'{http://sabredav.org/ns}email-address' => 'admin'
		];
		// TODO: Implement findByUri() method.
	}

	public function getGroupMemberSet($principal)
	{
		dd(__METHOD__);
		// TODO: Implement getGroupMemberSet() method.
	}

	public function getGroupMembership($principal)
	{
		dd(__METHOD__);
		// TODO: Implement getGroupMembership() method.
	}

	public function setGroupMemberSet($principal, array $members)
	{
		dd(__METHOD__);
		// TODO: Implement setGroupMemberSet() method.
	}
}