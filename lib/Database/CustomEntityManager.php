<?php

namespace Vorkfork\Database;

use Doctrine\ORM\EntityManager;

class CustomEntityManager extends EntityManager
{
    public function find($className, $id, $lockMode = null, $lockVersion = null) : Entity
    {
        return parent::find($className, $id, $lockMode, $lockVersion); // TODO: Change the autogenerated stub
    }

}