<?php
namespace Celeus\Apps\Mail\Controllers;
use Celeus\Core\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;

class MailController extends Controller {
    public function index(Request $request){
        dump($request);
        return 'Mail index page';
    }
}