<?php

namespace Vorkfork\Core\Repositories;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
use Vorkfork\Serializer\JsonSerializer;

class Repository extends EntityRepository {
	protected QueryBuilder $_qb;
	protected string $table;
	protected string $as;

	protected array $selectable = [];

	public function __construct(EntityManagerInterface $em, ClassMetadata $class) {
		parent::__construct($em, $class);
		//$this->_qb = $this->_em->createQueryBuilder();
		$this->table = $class->table['name'];
		$this->as = $this->table[0];
	}

	protected function addFrom() {
		$this->_qb->from($this->_class->name, $this->as);
	}

	public function select(array $fields = null): static {
		$this->_qb = $this->_em->createQueryBuilder();
		$this->addFrom();
		if(is_null($fields)){
			$this->_qb->select($this->as);
		} else{
			$i = 0;
			foreach($fields as $field) {
				if(!str_starts_with($this->as, $field).'.'){
					$fields[$i] = $this->as.'.'.$field;
				}
				$i++;
			}

			$this->_qb->select($fields);
		}

		return $this;
	}

	public function delete(): static {
		$this->_qb = $this->_em->createQueryBuilder();
		$this->_qb->delete($this->getClassName(), $this->as);

		return $this;
	}

	/**
	 * @param string $field
	 * @param string $comparator
	 * @param string $value
	 * @return $this
	 */
	public function where(string $field, string $comparator = '=', string $value = '') {
		$part = '';
		switch($comparator) {
			case Comparison::EQ:
				$part = $this->_qb->expr()->andX(
					$this->_qb->expr()->eq($this->as.'.'.$field, $value)
				);
				break;
			case Comparison::NEQ:
				$part = $this->_qb->expr()->not(
					$this->_qb->expr()->eq($this->as.'.'.$field, $value)
				);
				break;
		}
		$this->_qb->andWhere($part);
		return $this;
	}

	/**
	 * @param string $field
	 * @param array $values
	 * @return $this
	 */
	public function in(string $field, array $values) {
		if(!empty($values)){
			$this->_qb->add('where', $this->_qb->expr()->in($this->as.'.'.$field, $values));
		}
		return $this;
	}

	public function getSql(): ?string {
		return $this->_qb->getDQL();
	}

	/**
	 * @param string $field
	 * @param array $values
	 * @return static
	 */
	public function notIn(string $field, array $values): static {
		if(!empty($values)){
			$this->_qb->andWhere($this->_qb->expr()->notIn($this->as.'.'.$field, $values));
		}
		return $this;
	}

	/**
	 * @param int $start
	 * @return $this
	 */
	public function start(int $start): static {
		$this->_qb->setFirstResult($start);
		return $this;
	}

	/**
	 * @param int $limit
	 * @return $this
	 */
	public function limit(int $limit): static {
		$this->_qb->setMaxResults($limit);
		return $this;
	}

	public function orderBy(string $field, string $direction = 'DESC') {
		$this->_qb->orderBy($this->as.'.'.$field, $direction);
		return $this;
	}

	/**
	 * @return float|int|mixed|string
	 */
	public function results(string $dtoClass = null): mixed {
		$results = $this->_qb->getQuery()->getResult();
		return !is_null($dtoClass) ? JsonSerializer::deserializeArrayStatic($results, $dtoClass) : $results;
	}

	public static function __callStatic($name, $arguments) {
		dd($name, $arguments);
	}
}
