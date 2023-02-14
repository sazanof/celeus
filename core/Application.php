<?php

namespace Celeus\Core;

use Celeus\Application\ApplicationUtilities;
use Celeus\Core\Events\FillDatabaseAfterInstallEvent;
use Celeus\Core\Exceptions\EntityManagerNotDefinedException;
use Celeus\Database\CustomEntityManager;
use Celeus\Database\Database;
use Celeus\Database\IDatabase;
use Celeus\File\File;
use Celeus\Router\IRouter;
use Dotenv\Dotenv;
use \Exception;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Throwable;

class Application
{
    /**
     * @var IRouter
     */
    protected IRouter $router;
    protected ?IDatabase $connection = null;
    protected Dotenv $env;
    protected File $filesystem;
    protected ?CustomEntityManager $entityManager = null;
    protected ApplicationUtilities $utilities;
    public static string $configKey = 'core';
    private EventDispatcher $dispatcher;

    public function __construct(IRouter $router)
    {
        $this->router = $router;
        $this->filesystem = new File(realpath('../'));
        $this->env = Dotenv::createImmutable(realpath('../'));
        try {
            $this->env->load();
        } catch (Exception $e){

        };
        $this->utilities = new ApplicationUtilities();
        try {
            $this->connection = $this->initDatabaseConnection();
            $this->entityManager = $this->connection->getEntityManager();
            if(!is_null($this->entityManager)){
                $this->utilities->setEntityManager($this->entityManager);
            }
        } catch (\Doctrine\DBAL\Exception $e) {
        }

        $this->dispatcher = new EventDispatcher();
        $this->utilities->setDispatcher($this->dispatcher);
        $this->router->setDispatcher($this->dispatcher);
        $this->utilities->setRouter($this->router);
        $this->utilities->findApps(); // Register applications
    }

    public function checkUpdates(): void
    {
        if(!is_null($this->entityManager)){
            try {
                $this->utilities->checkVersion();
            } catch (EntityManagerNotDefinedException|Throwable $e) {
            }
        }

    }

    /**
     * @throws Throwable
     */
    //TODO add Router redirect to update process...
    public function isAppInstalled(): bool
    {
        return !$this->filesystem->exists('../config/NOT_INSTALLED')
            && $this->entityManager instanceof CustomEntityManager
            && $this->utilities->getVersion() === $this->utilities->getDatabaseAppVersion()->value;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    private function initDatabaseConnection(): IDatabase
    {
        return new Database();
    }

    public function watch(): RedirectResponse|bool
    {
        $request = Request::createFromGlobals();
        try {
            $matcher = $this->router->matchRoute($_SERVER['REQUEST_URI']);
            if (!$this->isAppInstalled() && !$matcher['public']) {
                return $this->router->redirectTo('/install');
            } else {
                if($this->isAppInstalled() && $matcher['_route'] === '/install/{step}'){
                    return $this->router->redirectTo('/');
                }
            }

            $controllerResolver = new ControllerResolver();
            $argumentResolver = new ArgumentResolver();
            $request->attributes->add($matcher);
            $controller = $controllerResolver->getController($request);
            $arguments = $argumentResolver->getArguments($request, $controller);
            $r = call_user_func_array($controller, $arguments);

            // TODO move to bottom to customize error pages
            //TODO - check middleware
            if(is_array($r) || is_object($r)){
                $response = new JsonResponse($r);
            } else {
                $response = new Response($r);
            }
            $response->send();
        } catch (ResourceNotFoundException $exception) {
            //TODO custom 404 page
            self::JsonResponseException($exception);
        } catch (MethodNotAllowedException $exception) {
            //TODO custom page
            self::JsonResponseException($exception);
        } catch (Exception|Throwable $exception) {
            self::JsonResponseException($exception);
        }
        return true;
    }

    private static function JsonResponseException(Exception|\Error $exception, $statusCode = 500): void
    {
        $response = new JsonResponse([
            'code' => $exception->getCode(),
            'message' => $exception->getMessage()
        ], $statusCode);
        $response->send();
    }


}
