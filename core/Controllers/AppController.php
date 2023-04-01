<?php
declare(strict_types=1);

namespace Vorkfork\Core\Controllers;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Vorkfork\Application\ApplicationUtilities;
use Vorkfork\Auth\Auth;
use Vorkfork\Core\Events\AddApplicationScripts;
use Vorkfork\Core\Translator\Translate;
use Vorkfork\DTO\UserDto;

class AppController extends Controller
{
	protected bool $useTemplateRenderer = true;


	public function index()
	{
		$apps = ApplicationUtilities::getInstance()->getApplicationsList();
		if (!empty($apps)) {
			return new RedirectResponse('/app/' . $apps[0]['name']);
		}
	}


	//TODO render app with custom template and js
	public function runApp($name)
	{
		//Получаем событие от шаблонизатора с массивом скриптов для подключения
		$this->dispatcher->addListener(AddApplicationScripts::NAME, function (AddApplicationScripts $event) {
			$this->data['scripts'] = $event->getScripts();
		});

		$path = realpath('./apps/' . $name . '/index.php');

		if ($path && $this->filesystem->exists($path)) {
			$apps = ApplicationUtilities::getInstance()->getApplicationsList();
			// TODO render JS to main template
			$this->data['application'] = require $path;
			$this->user = Auth::user();
			$this->data['user'] = $this->user;
			$this->data['title'] = Translate::t(ApplicationUtilities::getApplicationInformation($name)->information['name']);
			$menu = [];
			foreach ($apps as $app) {
				$menuItem = ApplicationUtilities::getApplicationInformation($app['name'])->information;
				if ($menuItem['showInMenu']) {
					$menu[] = ApplicationUtilities::getApplicationInformation($app['name'])->information;
				}
			}
			usort($menu, function ($a, $b) {
				return $a['order'] > $b['order'] ? 1 : -1;
			});
			$this->data['menu'] = $menu;
			return $this->templateRenderer->loadTemplate('/pages/main', $this->data);
		}
		throw new NotFoundHttpException();
	}

	/**
	 * @param $path
	 * @return Response|void
	 */
	public function fileResponse($path)
	{
		if (file_exists(realpath('../' . $path))) {
			$response = new Response();
			$root = realpath('../' . $path);
			$mime = mime_content_type($root);
			$basename = basename($root);
			$disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $basename);
			$response->headers->set('Content-Disposition', $disposition);
			$response->headers->set('Content-Type', $mime);
			$response->setContent(file_get_contents($root));
			return $response;
		}
	}
}
