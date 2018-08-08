<?php

namespace Model\Config\Db;

/**
 * Class DbModelConfig
 * @package Model\Config\Db
 */
class DbModelConfig implements IDbModelConfig
{
	/**
	 * @var string
	 */
	private $table;
	/**
	 * associative array of columns
	 * notated:
	 * $colName => $colMetaData
	 * @var DbModelColumnConfig[]
	 */
	private $columns;
	/**
	 * associative array of relations
	 * notated:
	 * $field => $relationMetaData
	 * @var DbModelRelationConfig[]
	 */
	private $relations;
	/**
	 * one of the columns to be PRIMARY KEY
	 * @var string
	 */
	private $primaryKey;
	/**
	 * @var array associative array of table constraints
	 */
	private $constraints;

	/**
	 * DbModelConfig constructor.
	 */
	public function __construct()
	{
	}

	/**
	 * @return mixed
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * @param mixed $table
	 * @return DbModelConfig
	 */
	public function setTable($table)
	{
		$this->table = $table;
		return $this;
	}

	/**
	 * @return DbModelColumnConfig[]
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * @param DbModelColumnConfig[] $columns
	 * @return DbModelConfig
	 */
	public function setColumns($columns)
	{
		$this->columns = $columns;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPrimaryKey(): string
	{
		return $this->primaryKey;
	}

	/**
	 * @param string $primaryKey
	 * @return DbModelConfig
	 */
	public function setPrimaryKey($primaryKey): DbModelConfig
	{
		$this->primaryKey = $primaryKey;
		return $this;
	}

	/**
	 * @return DbModelRelationConfig[]
	 */
	public function getRelations(): array
	{
		return $this->relations;
	}

	/**
	 * @param DbModelRelationConfig[] $relations
	 * @return DbModelConfig
	 */
	public function setRelations(array $relations): DbModelConfig
	{
		$this->relations = $relations;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getConstraints()
	{
		return $this->constraints;
	}

	public function setConstraints(array $constrains): DbModelConfig
	{
		$this->constraints = $constrains;
		return $this;
	}

	/**
	 * converts config class to an array for further parsing
	 * @return array
	 */
	function toArray()
	{
		return [
			'table'   => $this->table,
			'columns' => array_combine(array_keys($this->columns),
				array_map(function (DbModelColumnConfig $column) {
					return $column->toArray();
				}, $this->columns)),
		];
	}


}