<?php

namespace Vorkfork\Apps\Mail\Controllers;

use Vorkfork\Core\Controllers\Controller;
use Vorkfork\Core\Events\FillDatabaseAfterInstallEvent;
use Symfony\Component\HttpFoundation\Request;

class SettingsController extends Controller
{
	public function index(Request $request)
	{
		dump($request);
		return 'Calendar index page';
	}
}