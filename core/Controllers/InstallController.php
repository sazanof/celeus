<?php

namespace Vorkfork\Core\Controllers;

use Vorkfork\Application\ApplicationUtilities;
use Vorkfork\Core\Config\Config;
use Vorkfork\Core\Events\FillDatabaseAfterInstallEvent;
use Vorkfork\Core\Exceptions\UserAlreadyExistsException;
use Vorkfork\Core\Models\User;
use Vorkfork\Database\Database;
use Vorkfork\Database\Entity;
use Vorkfork\File\File;
use Vorkfork\Security\PasswordValidator;
use Vorkfork\Serializer\JsonSerializer;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\Mapping\MappingException;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InstallController extends Controller
{
    private ?User $admin = null;
    protected int $step;
    protected bool $useTemplateRenderer = true;
    protected Database $database;
    protected array $requiredExtensions = [
        'curl',
        'intl',
        'ldap',
        'mysqli',
        'pdo',
        'sodium'
    ];


    public function __construct()
    {
        parent::__construct();
        $this->step = 0;
        asort($this->requiredExtensions);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function install($step, Request $request)
    {
        // TODO add $request->isXmlHttpRequest() to AXIOS;
        switch ($step) {
            case 1:
                return $this->checkExtensions();
            case 2:
                return $this->checkDatabaseConnection($request);
            case 4:
                return $this->installApp($request);
            default:
                return $this->templateRenderer->loadTemplate('/install/install', [
                    'host' => env('APP_HOST', $request->getHost()),
                    'scheme' => env('APP_SCHEME', $request->getScheme())
                ]);
        }
    }

    public function checkExtensions(): array
    {
        $result = [];
        foreach ($this->requiredExtensions as $extension) {
            $result[] = [
                'extension' => $extension,
                'loaded' => extension_loaded($extension)
            ];
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    private function getDatabaseConnectionFromConfig($config): Database
    {
        $config = Config::fromArray($config);
        $this->database = new Database($config);
        return $this->database;
    }

    /**
     * @param Request $request
     * @return array
     * @throws Exception
     */
    #[ArrayShape(['success' => "bool"])]
    public function checkDatabaseConnection(Request $request): array
    {
        $config = Config::fromArray($request->request->all());
        $this->database = new Database($config);
        $success = false;
        if ($this->database->connection->connect()) {
            $success = true;
        }
        return [
            'success' => $success
        ];
    }

    /**
     * @throws Exception|MissingMappingDriverImplementation
     * @throws MappingException
     * @throws Throwable
     */
    public function installApp(Request $request)
    {
        $file = $this->createEnv($request);

        $connection = $request->get('connection');

        if (is_null($this->em)) {
            $this->em = $this->getDatabaseConnectionFromConfig($connection)->getEntityManager();
        }
        // Create schema
        $this->createSchema();
        // Fill config

        $admin = $request->get('admin');
        $existing = $this->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => $admin['email'],
                'username' => $admin['username']
            ]);
        if ($existing instanceof User) {
            throw new UserAlreadyExistsException();
        }
        if(!PasswordValidator::isDifficult($admin['password'])) {
            throw new InvalidPasswordException('The password does not meet the security requirements');
        }

        $this->admin = User::create($admin);

        $this->fillDatabaseAfterInstall();

        $result = [
            'env' => $file instanceof File,
            'schema' => Database::getInstance()->getEntityManager()->getConnection()->isConnected(),
            'admin' => $this->admin instanceof User
        ];
        $this->filesystem->remove('../config/NOT_INSTALLED');
        return JsonSerializer::serializeStatic($result);
    }

    public function createEnv(Request $request): File
    {
        $file = new File('../', '.env');
        try {
            $file->get();
        } catch (FileNotFoundException $exception) {
            $file->create();
        }

        $contents = $file->contentArray();
        $ar = [];
        foreach ($contents as $line) {
            $l = explode('=',$line);
            $ar[$l[0]] = $l[1];
        }

        $connection = $request->get('connection');
        $env = [
            'APP_HOST' => $request->getHost(),
            'APP_SCHEME' => $request->getScheme(),
            'APP_NAME' => '"My Vorkfork Project"',
            'APP_MODE' => 'production',
            'APP_WEBPACK_PROXY_HOST'=>$request->getScheme() . '://' . $request->getHost() . ':80',

            'DEFAULT_LOCALE' => 'ru',
            'DEFAULT_FALLBACK_LOCALE' => 'en',

            'DB_DRIVER' => $connection['driver'],
            'DB_HOST' => $connection['host'],
            'DB_PORT' => $connection['port'],
            'DB_DATABASE' => $connection['dbname'],
            'DB_TABLE_PREFIX' => $connection['prefix'],
            'DB_USER' => $connection['user'],
            'DB_PASSWORD' => $connection['password']
        ];
        // Существующие строки в файле не перезаписываются!
        $merged = array_merge($env, $ar); // from request $env, from existing $new
        ksort($merged);

        $contentToFile = '';
        foreach ($merged as $key => $value) {
            $contentToFile .= "{$key}={$value}" . PHP_EOL;
        }
        $file->dump($contentToFile);
        return $file;
    }

    /**
     * Drop and create database schema
     * @return void
     */
    private function createSchema(): void
    {
        $st = new SchemaTool($this->em);
        $files = Finder::create()->in('../core/Models')->name('*.php')->files();
        $meta = [];
        foreach ($files as $file) {
            $class = $file->getFilenameWithoutExtension();
            $meta[] = $this->em->getClassMetadata("\\Vorkfork\\Core\\Models\\{$class}");
        }
        $st->dropSchema($meta);
        $st->updateSchema($meta);
    }

    /**
     * @throws Throwable
     */
    private function fillDatabaseAfterInstall(): void
    {
        $utilities = ApplicationUtilities::getInstance();
        $event = new FillDatabaseAfterInstallEvent($this->admin, $utilities->getApplicationsList());
        $utilities->getDispatcher()->dispatch($event, FillDatabaseAfterInstallEvent::NAME);
    }
}