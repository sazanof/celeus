<?php

namespace Vorkfork\Apps\Dav\Auth;

use Sabre\DAV\Auth\Backend\BackendInterface;
use Sabre\HTTP\Auth\Basic;
use Sabre\HTTP\RequestInterface;
use Sabre\HTTP\ResponseInterface;
use Vorkfork\Auth\Auth;

class DavCustomAuth implements BackendInterface
{
	protected $principalPrefix = 'principals/';
	protected $realm = 'sabre/dav';

	protected function validateUserPass($username, $password): bool
	{
		return Auth::check($username, $password);

	}

	/**
	 * When this method is called, the backend must check if authentication was
	 * successful.
	 *
	 * The returned value must be one of the following
	 *
	 * [true, "principals/username"]
	 * [false, "reason for failure"]
	 *
	 * If authentication was successful, it's expected that the authentication
	 * backend returns a so-called principal url.
	 *
	 * Examples of a principal url:
	 *
	 * principals/admin
	 * principals/user1
	 * principals/users/joe
	 * principals/uid/123457
	 *
	 * If you don't use WebDAV ACL (RFC3744) we recommend that you simply
	 * return a string such as:
	 *
	 * principals/users/[username]
	 *
	 * @return array
	 */
	public function check(RequestInterface $request, ResponseInterface $response)
	{
		if (Auth::isAuthenticated()) {
			return [true, $this->principalPrefix . Auth::user()->username];
		}
		$auth = new Basic(
			$this->realm,
			$request,
			$response
		);

		$userpass = $auth->getCredentials();
		if (!$userpass) {
			return [false, "No 'Authorization: Basic' header found. Either the client didn't send one, or the server is misconfigured"];
		}
		if (!$this->validateUserPass($userpass[0], $userpass[1])) {
			return [false, 'Username or password was incorrect'];
		}
		return [true, $this->principalPrefix . $userpass[0]];
	}

	/**
	 * This method is called when a user could not be authenticated, and
	 * authentication was required for the current request.
	 *
	 * This gives you the opportunity to set authentication headers. The 401
	 * status code will already be set.
	 *
	 * In this case of Basic Auth, this would for example mean that the
	 * following header needs to be set:
	 *
	 * $response->addHeader('WWW-Authenticate', 'Basic realm=SabreDAV');
	 *
	 * Keep in mind that in the case of multiple authentication backends, other
	 * WWW-Authenticate headers may already have been set, and you'll want to
	 * append your own WWW-Authenticate header instead of overwriting the
	 * existing one.
	 */
	public function challenge(RequestInterface $request, ResponseInterface $response)
	{
		$auth = new Basic(
			$this->realm,
			$request,
			$response
		);
		$auth->requireLogin();
	}
}
