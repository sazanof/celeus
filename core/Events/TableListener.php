<?php

namespace Vorkfork\Core\Events;

use \Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class TableListener
{
	protected string $prefix = '';
	protected array $options = [];
	protected string $charset = '';

	public function __construct(string $prefix, string $charset, array $options)
	{
		$this->prefix = $prefix;
		$this->charset = $charset;
		$this->options = $options;
	}

	public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
	{
		$classMetadata = $eventArgs->getClassMetadata();

		$table = $classMetadata->table;
		$options = isset($table['options']) ? array_merge($table['options'], $this->options) : $this->options;

		if (!$classMetadata->isInheritanceTypeSingleTable() || $classMetadata->getName() === $classMetadata->rootEntityName) {
			$classMetadata->setPrimaryTable([
				'name' => $this->prefix . $classMetadata->getTableName(),
				'charset' => $this->charset,
				'options' => ['collation' => $this->options['collation']]
			]);
		}

		foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
			if ($mapping['type'] == ClassMetadataInfo::MANY_TO_MANY && $mapping['isOwningSide']) {
				$mappedTableName = $mapping['joinTable']['name'];
				$classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
			}
		}
	}

}
