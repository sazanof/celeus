<?php

namespace Vorkfork\Apps\Dav\Controllers;

use Sabre\DAV\Locks\Backend\File;
use Sabre\DAV\Server;
use Symfony\Component\HttpFoundation\Request;
use Vorkfork\Apps\Dav\Auth\DavCustomAuth;
use Vorkfork\Apps\Dav\Backend\PrincipalsBackend;
use Vorkfork\Apps\Dav\Collections\SharesCollection;
use Vorkfork\Core\Config\Config;
use Vorkfork\Core\Controllers\Controller;
use Vorkfork\Core\DAV\DavConfigurator;

class DavController extends Controller
{
	protected Config $davConfig;

	public function __construct()
	{
		$this->davConfig = DavConfigurator::getInstance();
		parent::__construct();
	}

	/**
	 * @throws \Sabre\DAV\Exception
	 */
	public function server(Request $request)
	{
		$principalBackend = new PrincipalsBackend();

		$tree = [
			new SharesCollection($principalBackend, $this->davConfig->getSharesPath()),
		];
		$server = new Server($tree);
		$server->setBaseUri($this->davConfig->getBaseUri());
		$lockBackend = new File($this->davConfig->getLocks());
		$server->addPlugin(new \Sabre\DAV\Locks\Plugin($lockBackend));
		$server->addPlugin(new \Sabre\DAV\Auth\Plugin(new DavCustomAuth()));
		$server->addPlugin(new \Sabre\DAVACL\Plugin());
		$server->addPlugin(new \Sabre\DAV\Browser\Plugin());
		$server->start();
		exit;
	}
}