<?php

namespace Celeus\Core\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

class CeleusRepository extends EntityRepository
{
    protected QueryBuilder $_qb;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->_qb = $this->_em->createQueryBuilder();
    }

    public static function __callStatic($name, $arguments){
        dd($name, $arguments);
    }
}