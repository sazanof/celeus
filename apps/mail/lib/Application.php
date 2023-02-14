<?php

namespace Celeus\Apps\Mail;

use Celeus\Application\ApplicationUtilities;
use Celeus\Apps\Mail\Repositories\MailPermissions;
use Celeus\Core\Events\FillDatabaseAfterInstallEvent;
use Celeus\Core\Models\Group;
use Celeus\Core\Models\Permissions;
use Celeus\Database\CustomEntityManager;
use Celeus\Database\Database;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Application
{
    protected EventDispatcher $dispatcher;
    protected EntityManager|CustomEntityManager|null $entityManager;
    protected MailPermissions $mailRepository;
    private Database $database;

    /**
     * @throws MissingMappingDriverImplementation
     */
    public function __construct(ApplicationUtilities $utilities)
    {
        $this->dispatcher = $utilities->getDispatcher();
        $this->database = $utilities->getDatabase();
        $this->entityManager = $this->database->getEntityManager();
        $this->registerListeners();
    }

    /**
     * @return void
     */
    public function registerListeners(): void
    {
        // Start listen to After install Events
        $this->dispatcher->addListener(FillDatabaseAfterInstallEvent::NAME, function (FillDatabaseAfterInstallEvent $event) {
            //dd($event);
            MailPermissions::insertDefaultMailPermissions();
            $group = Group::find(($event->getAdminGroup()->getId()));
            $group->addPermissions(Permissions::repository()
                ->select()
                ->whereNameLike('mail.%')
                ->results()
            );
            $group->em()->persist($group);
            $group->em()->flush($group);
        });

    }
}