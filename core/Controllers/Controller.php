<?php

namespace Vorkfork\Core\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vorkfork\Application\ApplicationUtilities;
use Vorkfork\Auth\Auth;
use Vorkfork\Controller\IController;
use Vorkfork\Core\Templates\TemplateRenderer;
use Vorkfork\Core\Translator\Locale;
use Vorkfork\Database\Database;
use Vorkfork\Database\Entity;
use Vorkfork\DTO\BaseDto;
use Vorkfork\File\File;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Vorkfork\Response\TokenMismatchResponse;
use Vorkfork\Security\CSRFToken;

class Controller implements IController
{
	protected Request $request;
	protected TemplateRenderer $templateRenderer;
	protected File $filesystem;
	protected ?EntityManager $em;
	protected EventDispatcher $dispatcher;
	protected ParameterBag $attributes;
	protected string $title;
	protected ?BaseDto $user = null;
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
		$this->dispatcher->addListener(KernelEvents::FINISH_REQUEST, function (FinishRequestEvent $event) {
			$request = $event->getRequest();
			$method = $request->getMethod();
			if ($method === Request::METHOD_POST || $method === Request::METHOD_PUT) {
				$err = true;
				$token = $request->headers->get('x-csrf-token');
				if (CSRFToken::verify($token)) {
					$err = false;
				}
				if ($err) {
					$r = new TokenMismatchResponse();
					$r->send();
					$event->stopPropagation();
				}
			}
		});
		$this->dispatcher->addListener(KernelEvents::VIEW, function (ViewEvent $event) {
			$controllerResult = $event->getControllerResult();
			if (is_string($controllerResult)) {
				$event->setResponse(new Response($controllerResult));
			} elseif (is_array($controllerResult) || is_object($controllerResult)) {
				if ($controllerResult instanceof Entity) {
					$event->setResponse(new Response($controllerResult->toJSON()));
				} else {
					$event->setResponse(new JsonResponse($controllerResult));
				}
			} elseif (is_bool($controllerResult)) {
				$event->setResponse(new JsonResponse(['success' => $controllerResult]));
			} elseif (is_null($controllerResult)) {
				$event->setResponse(new Response());
			}
		});
		$this->dispatcher->addListener(KernelEvents::CONTROLLER, function (ControllerEvent $event) {
			$this->attributes = $event->getRequest()->attributes;
			$this->title = $this->attributes->get('title') ?? '';
			$this->data['title'] = $this->title;
			$this->data['currentRoute'] = $event->getRequest()->getPathInfo();
			$this->data['needAuth'] = $this->needAuth();
			$this->data['locale'] = Locale::getDefaultLocale();
			if ($this->needAuth()) {
				$this->data['isAuthenticated'] = Auth::isAuthenticated();
			}
			$this->data['host'] = env('APP_HOST', $event->getRequest()->getHost());
			$this->data['scheme'] = env('APP_SCHEME', $event->getRequest()->getScheme());
			$request = $event->getRequest();
			if ($request->getMethod() === Request::METHOD_GET && is_null($request->headers->get('x-ajax-call'))) {
				$token = CSRFToken::generate();
			} else {
				$token = CSRFToken::getToken();
			}
			$this->data['token'] = $token;
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
