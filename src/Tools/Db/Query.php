<?php

namespace Tools\Db;

use Enum\QueryType;

/**
 * Class representing a database Query
 * would be implementing a strategy pattern if only more time was available
 *
 * @package \Tools\Db
 */
class Query
{

	//region Fields
	/**
	 * @var string
	 */
	private $table;
	/**
	 * @var string the type of the query. One of Enum\QueryType
	 */
	private $type;

	/**
	 * @var IDbAdapter db engine independent adapter to handle all operations
	 *ANSI SQL92
	 */
	private $dbAdapter;

	/**
	 * @var array associative array of parameters for SELECT, INSERT & UPDATE queries.
	 * For SELECT it's list of columns to be fetched. May be column => alias or just column
	 * For INSERT it's assignment of column => value
	 * For UPDATE it's assignment of column => value
	 */
	private $parameters;

	/**
	 * @var array
	 * Tables to be fetched from
	 * Should be in format table_name => condition of join
	 */
	private $joinTables;

	/**
	 * @var array
	 * Tables to be fetched from
	 * Should be in format table_name => condition of join
	 */
	private $leftOuterJoinTables;

	/**
	 * @var array all conditions for SELECT, UPDATE and DELETE queries
	 * All of them will be separated by AND
	 * On OR necessity, should be declared as a single conditions ie. ["id=1 OR name='Kiejstut'", 'active=true']
	 */
	private $conditions;
	/**
	 * @var string
	 */
	private $sort;
	/**
	 * @var array
	 */
	private $fields;
	/**
	 * @var int
	 */
	private $limit;
	/**
	 * @var int
	 */
	private $offset;
	//endregion

	//region Constructor
	/**
	 * Query constructor.
	 * @param IDbAdapter $dbAdapter
	 * @param string     $type one of Enum\QueryType
	 * @param string     $table
	 */
	public function __construct(IDbAdapter $dbAdapter, string $type, string $table = null)
	{
		$this->dbAdapter  = $dbAdapter;
		$this->type       = $type;
		$this->table      = $table;
		$this->joinTables = [];
	}

	//endregion

	//region Methods
	//region Getters and setters
	/**
	 * @return string
	 */
	public function getTable(): string
	{
		return $this->table;
	}

	/**
	 * @param string $table
	 * @return Query
	 */
	public function setTable(string $table): Query
	{
		$this->table = $table;
		return $this;
	}

	/**
	 * @return \string[]
	 */
	public function getFields(): array
	{
		return $this->fields;
	}

	/**
	 * @param \string[] $fields
	 * @return Query
	 */
	public function setFields(array $fields): Query
	{
		$this->fields = $fields;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @param array $parameters
	 * @return Query
	 */
	public function setParameters(array $parameters)
	{
		$this->parameters = $parameters;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getJoinTables(): array
	{
		return $this->joinTables;
	}

	/*
	 * @param array $joinTables ['table'=>'condition] or just tables for cross join
	 * @return Query
	 */
	public function setJoinTables(array $joinTables): self
	{
		$this->joinTables = $joinTables;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getLeftOuterJoinTables(): array
	{
		return $this->leftOuterJoinTables;
	}

	/**
	 * @param array $leftOuterJoinTables
	 * @return Query
	 */
	public function setLeftOuterJoinTables(array $leftOuterJoinTables): self
	{
		$this->leftOuterJoinTables = $leftOuterJoinTables;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getConditions(): array
	{
		return $this->conditions;
	}

	/**
	 * @param array $conditions all conditions for SELECT, UPDATE and DELETE queries
	 * All of them will be separated by AND
	 * On OR necessity, should be declared as a single conditions ie. ["id=1 OR name='Kiejstut'", 'active=true']
	 * @return Query
	 */
	public function setConditions(array $conditions): self
	{
		$this->conditions = $conditions;
		return $this;
	}

	public function addCondition(string $condition): self
	{
		$this->conditions[] = $condition;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSort(): string
	{
		return $this->sort;
	}

	/**
	 * @param string $sort
	 * @return Query
	 */
	public function setSort(string $sort): Query
	{
		$this->sort = $sort;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLimit(): int
	{
		return $this->limit;
	}

	/**
	 * @param int $limit
	 * @return Query
	 */
	public function setLimit(int $limit): Query
	{
		$this->limit = $limit;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getOffset(): int
	{
		return $this->offset;
	}

	/**
	 * @param int $offset
	 * @return Query
	 */
	public function setOffset(int $offset): Query
	{
		$this->offset = $offset;
		return $this;
	}

	//endregion


	public function getResults()
	{

	}

	public function getOneOrNullResult()
	{

	}

	public function query()
	{

	}

	/**
	 * @inheritDoc
	 */
	function __toString()
	{
		$words = [strtoupper($this->type)];
		switch ($this->type) {
			//missing break is on purpose
			case QueryType::SELECT:
				$words[] = $this->fields ? join(', ', $this->fields) : '*';
			case QueryType::DELETE:
				$words[] = 'FROM';
				break;
			case QueryType::INSERT:
				$words[] = 'INTO';
		}
		$words[] = $this->table;
		switch ($this->type) {
			case QueryType::INSERT:
				$words[] = sprintf('(%s)', join(', ', array_keys($this->parameters)));
				$words[] = sprintf('VALUES (%s)', join(', ', $this->parameters));
				break;
			case QueryType::UPDATE:
				$words[] = 'SET ' . join(', ',
						array_map(function ($target, $value) {
							return "$target=$value";
						}, array_keys($this->parameters), $this->parameters)
					);
				break;
			case QueryType::SELECT:
				if ($this->joinTables) {
					$words[] = join(' ', array_map(function (string $joinTable) {
						return 'JOIN ' . $joinTable;
					}, $this->joinTables));
				}
				if ($this->leftOuterJoinTables) {
					$words[] = join(' ', array_map(function (string $joinTable) {
						return 'LEFT OUTER JOIN ' . $joinTable;
					}, $this->leftOuterJoinTables));
				}
		}

		if ($this->conditions) {
			$words[] = 'WHERE ' . join(' AND ', array_map(function ($key, $value) {
					return is_numeric($key) ? $value : "$key=$value";
				}, array_keys($this->conditions), $this->conditions));
		}

		if ($this->type == QueryType::SELECT) {
			if ($this->sort) {
				$words[] = 'ORDER BY ' . $this->sort;
			}
			if ($this->limit) {
				$words[] = 'LIMIT ' . $this->limit;
			}
			if ($this->offset) {
				$words[] = 'OFFSET ' . $this->offset;
			}
		}

		return join(' ', $words);
		/**
		 * TODO:
		 * Implement grouping
		 */
//endregion
	}


}