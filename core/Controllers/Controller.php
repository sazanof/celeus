<?php

namespace Vorkfork\Core\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\EventDispatcher\Event;
use Vorkfork\Application\ApplicationUtilities;
use Vorkfork\Controller\IController;
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
    protected ParameterBag $attributes;
    protected string $title;
    protected array $data = [];

    protected bool $useTemplateRenderer = false;


    public function __construct()
    {
        $this->filesystem = new File();
        $this->em = Database::getInstance()->getEntityManager();
        if ($this->useTemplateRenderer) {
            $this->templateRenderer = new TemplateRenderer();
        }
        $this->dispatcher = ApplicationUtilities::getInstance()->getDispatcher();
        /**
         * Listen to kernel.* events
         */
        $this->dispatcher->addListener(KernelEvents::VIEW, function (ViewEvent $event) {
            $controllerResult = $event->getControllerResult();
            if (is_string($controllerResult)) {
                $event->setResponse(new Response($controllerResult));
            } elseif (is_array($controllerResult) || is_object($controllerResult)) {
                $event->setResponse(new JsonResponse($controllerResult));
            }
        });
        $this->dispatcher->addListener(KernelEvents::CONTROLLER, function (ControllerEvent $event) {
            $this->attributes = $event->getRequest()->attributes;
            $this->title = $this->attributes->get('title') ?? '';
            $this->data['title'] = $this->title;
            $this->data['needAuth'] = $this->needAuth();
            $this->data['host'] = env('APP_HOST', $event->getRequest()->getHost());
            $this->data['scheme'] = env('APP_SCHEME', $event->getRequest()->getScheme());
        });
        $this->dispatcher->addListener(KernelEvents::RESPONSE, function (ResponseEvent $event) {

        });
    }

    public static function asResponse(mixed $any): Response
    {
        if (is_string($any)) {
            return new Response($any);
        } elseif (is_array($any) || is_object($any)) {
            return new JsonResponse($any);
        }

    }

    public function execute($className, $method, $params = [])
    {
        return call_user_func(array($className, $method), $params);
    }

    protected function needAuth()
    {
        return $this->attributes->get('auth') ?? false;
    }
}
