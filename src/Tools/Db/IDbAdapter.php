<?php

namespace Tools\Db;

use Model\Config\Db\DbModelConfig;

interface IDbAdapter
{
	function init(): self;

	function connect(): self;

	/**
	 * @param string $query
	 * @param array  $parameters
	 * @param string $class
	 * @return array|null array of rows or null
	 * #@throws \Exception
	 */
	function fetchRows(string $query, array $parameters = null, string $class = null): array;

	/**
	 * @param string $query
	 * @param array  $parameters
	 * @return bool
	 */
	function executeQuery(string $query, array $parameters = null): bool;

	/**
	 * @param DbModelConfig $config
	 * @return string
	 */
	function createTableQuery(DbModelConfig $config): string;

	/**
	 * @param DbModelConfig $config
	 * @param bool          $cascade
	 * @param bool          $ifExists
	 * @return string
	 */
	function dropTableQuery(DbModelConfig $config, $cascade = true, $ifExists = true): string;

	/**
	 * Parse parameters db engine-specific (escape)
	 * @param array $conditions
	 * @return array
	 */
	function parseParameters(array $conditions): array;

	/**
	 * @return bool
	 */
	public function beginTransaction():bool;

	/**
	 * @return bool
	 */
	public function commit():bool;

	/**
	 * @return bool
	 */
	public function rollBack() : bool;

}