<?php

namespace Vorkfork\Database;

use Vorkfork\Config\IConfig;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;

interface IDatabase
{
    public function getConfig() : array;

    public function connect() : Connection|null;

    public function getEntityManager() : CustomEntityManager|null;

    public function chooseDriver() : self;
}