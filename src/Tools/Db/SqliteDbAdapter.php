<?php

namespace Tools\Db;

use Enum\ConstraintType;
use Enum\Model\Config\ColumnType;
use Enum\Model\RelationType;
use Model\Config\Db\DbModelColumnConfig;
use Model\Config\Db\DbModelConfig;
use Model\Config\Db\DbModelRelationConfig;

/**
 * Class SqliteDbAdapter
 * not sure if there are so many sql differences for other DB engines to create such hierarchy when queries would be ANSI SQL92 compliant
 *
 * @package \Tools\Db
 */
class SqliteDbAdapter extends AbstractDbAdapter implements IDbAdapter
{

	/**
	 * @inheritdoc
	 */
	public function createTableQuery(DbModelConfig $config): string
	{
		$columnNames              = array_keys($config->getColumns());
		$columnDefinitionsIndexed = array_combine($columnNames, array_map([$this, 'extractDbColumnDefinition'], $config->getColumns()));
		$primaryKey               = $config->getPrimaryKey();
		$primaryKeyStatement      = sprintf('PRIMARY KEY (%s)', $primaryKey);
		$columnDefinitions        = array_map(function ($columnName, $columnDefinition) {
			return "$columnName $columnDefinition";
		}, $columnNames, $columnDefinitionsIndexed);
		$relations                = array_filter($config->getRelations(), function (DbModelRelationConfig $relation) {
			return $relation->getRelationType() == RelationType::MANY_TO_ONE;
		});
		$relationsDefinitions     = array_map(function (DbModelRelationConfig $relation) {
			return sprintf('FOREIGN KEY(%s) REFERENCES %s(%s)', $relation->getId(), $relation->getTargetConfiguration()->getTable(), $relation->getTargetConfiguration()->getPrimaryKey());
		}, $relations);
		$uniqueConstraintDefinitions = array_map(function (array $uniqueConstraintColumns) {
			return sprintf('CONSTRAINT %s UNIQUE (%s)', "unique_" . join('_', $uniqueConstraintColumns), join(',', $uniqueConstraintColumns));
		}, $config->getConstraints()[ConstraintType::UNIQUE]);
		$result = sprintf("CREATE TABLE %s(%s)", $config->getTable(), join(',' . PHP_EOL, array_merge($columnDefinitions, [$primaryKeyStatement], $relationsDefinitions, $uniqueConstraintDefinitions)));
		return $result;
	}

	/**
	 * @inheritdoc
	 */
	public function dropTableQuery(DbModelConfig $config, $cascade = true, $ifExists = true): string
	{
		$parts = [
			'DROP TABLE',
			$ifExists ? 'IF EXISTS' : null,
			$config->getTable(),
			$cascade ? ' CASCADE' : null,
		];
		return join(' ', array_filter($parts));
	}

	/**
	 * @inheritDoc
	 */
	function parseParameters(array $conditions): array
	{
		/**
		 * TODO: stringing functions is not my favorite way to implement mappings and similar
		 */
		return array_combine(array_keys($conditions), array_map('sqlite_escape_string', $conditions));
	}

	/**
	 * @param DbModelColumnConfig $dbColumnConfig
	 * @return string
	 */
	protected function extractDbColumnDefinition(DbModelColumnConfig $dbColumnConfig): string
	{
		$result = [$dbColumnConfig->getType()];
		switch ($dbColumnConfig->getType()) {
			case ColumnType::VARCHAR:
				if ($dbColumnConfig->getLength()) {
					$result[] = sprintf('(%s)', $dbColumnConfig->getLength());
				}
				break;
		}
		if ($dbColumnConfig->getNull() !== null) {
			$result [] = $dbColumnConfig->getNull() ? 'NULL' : 'NOT NULL';
		}
		return join(' ', $result);
	}
}