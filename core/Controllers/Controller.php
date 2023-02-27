<?php

namespace Vorkfork\Core\Controllers;

use Vorkfork\Application\ApplicationUtilities;
use Vorkfork\Controller\IController;
use Vorkfork\Core\Application;
use Vorkfork\Core\Templates\TemplateRenderer;
use Vorkfork\Database\Database;
use Vorkfork\File\File;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

class Controller implements IController
{
    protected Request $request;
    protected TemplateRenderer $templateRenderer;
    protected File $filesystem;
    protected ?EntityManager $em;
    protected EventDispatcher $dispatcher;

    protected bool $useTemplateRenderer = false;


    public function __construct()
    {
        $this->filesystem = new File();
        $this->em = Database::getInstance()->getEntityManager();
        if($this->useTemplateRenderer){
            $this->templateRenderer = new TemplateRenderer();
        }
        $this->dispatcher = ApplicationUtilities::getInstance()->getDispatcher();
    }

    public function execute($className, $method, $params = [])
    {
        return call_user_func(array($className, $method), $params);
    }
}
