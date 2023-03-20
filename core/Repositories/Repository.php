<?php

namespace Vorkfork\Core\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

class Repository extends EntityRepository
{
    protected QueryBuilder $_qb;
    protected string $table;
    protected string $as;

    protected array $selectable = [];

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->_qb = $this->_em->createQueryBuilder();
        $this->table = $class->table['name'];
        $this->as = $this->table[0];
    }

    protected function addFrom()
    {
        $this->_qb->from($this->_class->name, $this->as);
    }

    public function select(array $fields = null): static
    {
        $this->addFrom();
        if (is_null($fields)) {
            $this->_qb->select($this->as);
        } else {
            $i = 0;
            foreach ($fields as $field) {
                if (!str_starts_with($this->as, $field) . '.') {
                    $fields[$i] = $this->as . '.' . $field;
                }
                $i++;
            }

            $this->_qb->select($fields);
        }

        return $this;
    }

    /**
     * @return float|int|mixed|string
     */
    public function results(): mixed
    {
        return $this->_qb->getQuery()->getResult();
    }

    public static function __callStatic($name, $arguments)
    {
        dd($name, $arguments);
    }
}