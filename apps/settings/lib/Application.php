<?php

namespace Vorkfork\Apps\Settings;

use Vorkfork\Application\ApplicationUtilities;
use Vorkfork\Apps\Settings\Repositories\SettingsRepository;
use Vorkfork\Core\Events\FillDatabaseAfterInstallEvent;
use Vorkfork\Core\Models\Group;
use Vorkfork\Core\Models\Permissions;
use Vorkfork\Database\CustomEntityManager;
use Vorkfork\Database\Database;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Application
{
	protected EventDispatcher $dispatcher;
	protected EntityManager|CustomEntityManager|null $entityManager;
	private Database $database;

	/**
	 * @throws MissingMappingDriverImplementation
	 */
	public function __construct(ApplicationUtilities $utilities)
	{
		$this->dispatcher = $utilities->getDispatcher();
		$this->database = $utilities->getDatabase();
		$this->entityManager = $this->database->getEntityManager();
		$this->installApplication();
	}

	/**
	 * Install application after
	 * @return void
	 */
	public function installApplication(): void
	{
		$this->dispatcher->addListener(FillDatabaseAfterInstallEvent::NAME, function (FillDatabaseAfterInstallEvent $event) {
			SettingsRepository::insertDefaultSettingsPermissions();
			/** @var Group $group */
			$group = Group::find(($event->getAdminGroup()->getId()));
			try {
				$group->addPermissions(
					Permissions::repository()
						->select()
						->whereNameLike('settings.%')
						->results()
				);
				$group->em()->persist($group);
				$group->em()->flush($group);
			} catch (\Exception $e) {
				dd($e);
				ApplicationUtilities::errorResponse($e->getMessage())->send();
			}
		});

	}
}