<?php

namespace Model\Config;

use Model\IDbModel;

/**
 * Class AbstractDbModel
 * Parody of ActiveRecords
 *
 * @package \Model\Config
 */
abstract class AbstractDbModel implements IDbModel
{
	/**
	 * @inheritDoc
	 */
	public function getId()
	{
		$primaryKey       = null;
		$primaryKeyColumn = $this::getConfig(true)->getPrimaryKey();
		if (strpos($primaryKeyColumn, ',') !== FALSE) {
			$keys       = explode(',', $primaryKeyColumn);
			$primaryKey = array_map(function ($key) {
				return $this->$key;
			}, $keys);
		} else {
			$primaryKey = $this->$primaryKeyColumn;
		}
		return $primaryKey;
	}

	/**
	 * @inheritDoc
	 */
	function setColumnValue(string $columnName, $value): IDbModel
	{
		$this->$columnName = $value;
		return $this;
	}


	/**
	 * @inheritDoc
	 */
	function toArray(bool $omitPrimaryKey = false): array
	{
		$columnNames = array_keys($this::getConfig()->getColumns());
		if ($omitPrimaryKey) {
			$columnNames = array_diff($columnNames, [$this::getConfig()->getPrimaryKey()]);
		}
		$values = array_map(function ($columnName) {
			return $this->$columnName;
		}, $columnNames);
		return array_combine($columnNames, $values);
	}

}