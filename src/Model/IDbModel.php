<?php

namespace Model;

use Model\Config\Db\DbModelConfig;

interface IDbModel
{

	/**
	 * Configuration to be used in repository
	 * @param bool $withRelations
	 * @return DbModelConfig
	 */
	static function getConfig(bool $withRelations = true): DbModelConfig;

	/**
	 * Generic primary key value extraction
	 * @return int|array|null  array for multi column keys. If null then something went wrong
	 */
	public function getId();

	/**
	 * setter of column values to prevent 'hacking' of getter and setter values
	 * @param string $columnName
	 * @param mixed  $value
	 * @return IDbModel
	 */
	function setColumnValue(string $columnName, $value): self;

	/**
	 * converts all columns with values to array
	 * @param bool $omitPrimaryKey useful for inserts
	 * @return array
	 */
	function toArray(bool $omitPrimaryKey = false): array;

}