<?php

namespace Vorkfork\Apps\Settings;

use Vorkfork\Application\ApplicationUtilities;
use Vorkfork\Core\Events\FillDatabaseAfterInstallEvent;
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

		});

	}
}