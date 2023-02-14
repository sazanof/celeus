<?php

namespace Celeus\Apps\Mail\Controllers;

use Celeus\Core\Controllers\Controller;
use Celeus\Core\Events\FillDatabaseAfterInstallEvent;
use Symfony\Component\HttpFoundation\Request;

class MailController extends Controller
{
    public function index(Request $request)
    {
        dump($request);
        //EXAMPLE
        // 1 - add listener in one controller method
        // $this->dispatcher->addListener(FillDatabaseAfterInstallEvent::NAME, function ($app){
        //    dump($app);
        // });
        //2 - dispatch an event in other controller
        //$this->dispatcher->dispatch((new FillDatabaseAfterInstallEvent('var_event')),FillDatabaseAfterInstallEvent::NAME);
        return 'Mail index page';
    }
}