<?php
namespace Celeus\Core\Events;
use Celeus\Application\ApplicationUtilities;
use Celeus\Core\Application;
use Celeus\Core\Models\Config;
use Celeus\Core\Models\Group;
use Celeus\Core\Models\Permissions;
use Celeus\Core\Models\User;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\Mapping\MappingException;
use Symfony\Contracts\EventDispatcher\Event;
use Throwable;

class FillDatabaseAfterInstallEvent extends Event {
    public const NAME = 'fill.database.after.install';
    private ApplicationUtilities $utilities;
    private ?User $admin;
    private ?Group $group;

    /**
     * @throws OptimisticLockException
     * @throws MappingException
     * @throws ORMException
     * @throws Throwable
     */
    public function __construct(User $admin, array $applicationsList)
    {
        $this->utilities = ApplicationUtilities::getInstance();
        $this->admin = $admin;
        $this->utilities->getEntityManager()->wrapInTransaction(function (){
            $this->fillConfig();
            $this->fillCorePermissions();
            $this->createAdminGroup();
            $this->addAdminToAdminGroup();
            $this->setPermissionsToAdminGroup();
            $this->utilities->getEntityManager()->flush();
        });
        return [];

        //dd(__CLASS__,__METHOD__, $applicationsList);
        /*foreach ($applicationsList as $app){

        }*/
    }

    public function getAdmin(){
        return $this->admin;
    }

    public function getAdminGroup(): ?Group
    {
        return $this->group;
    }

    /**
     * @throws OptimisticLockException
     * @throws MappingException
     * @throws ORMException
     */
    private function fillConfig(){
        Config::insertBulk([
            [
                'app' => Application::$configKey,
                'key' => 'version',
                'value' => $this->utilities->getVersion()
            ],
            [
                'app' => Application::$configKey,
                'key' => 'timezone',
                'value' => $this->utilities->getDefaultTimezone()
            ]
        ]);
    }

    /**
     * @throws MappingException
     */
    private function fillCorePermissions(){
        Permissions::repository()->insertDefaultPermissions();
    }

    /**
     * @throws MappingException
     */
    private function createAdminGroup(){
        $this->group = Group::create([
            'name'=>'Administrators'
        ]);
    }

    /**
     * @return void
     */
    private function addAdminToAdminGroup(): void
    {
        $a = User::repository()->find($this->admin->getId());
        $a->setGroups([$this->group]);
    }

    private function setPermissionsToAdminGroup(){
        $permissions = Permissions::repository()->findAll();
        $this->group->setPermissions($permissions);
    }
}