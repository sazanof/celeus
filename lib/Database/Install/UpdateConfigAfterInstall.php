<?php

namespace Celeus\Database\Install;

use Celeus\Application\ApplicationUtilities;
use Celeus\Core\Application;
use Celeus\Core\Config\Config;
use Celeus\Core\Exceptions\EntityAlreadyExistsException;
use Celeus\Core\Models\Config as ORMConfig;
use Celeus\Core\Models\Permissions;
use Doctrine\ORM\EntityManager;

class UpdateConfigAfterInstall
{
    protected Config $config;
    protected ?EntityManager $entityManager = null;
    protected ApplicationUtilities $utilities;

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function __construct()
    {
        $this->utilities = ApplicationUtilities::getInstance();
        $this->entityManager = $this->utilities->getEntityManager();
    }

    /**
     * @throws \Throwable
     */
    public function addBaseConfigValues(): bool
    {
        $this->entityManager->getRepository(ORMConfig::class);

        ORMConfig::create([
            'app' => Application::$configKey,
            'key' => 'version',
            'value' => $this->utilities->getVersion()
        ]);

        ORMConfig::create([
            'app' => Application::$configKey,
            'key' => 'timezone',
            'value' => $this->utilities->getDefaultTimezone()
        ]);

        Permissions::repository()->insertDefaultPermissions(Application::$configKey);

        return true;
    }
}