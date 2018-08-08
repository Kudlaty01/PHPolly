<?php

namespace Tools\Db;

use Enum\Config\Db;
use Enum\QueryType;
use Model\Config\Db\DbModelColumnConfig;
use Model\Config\Db\DbModelConfig;

/**
 * abstract class AbstractDbAdapter
 *
 * @package \Tools\Db
 */
abstract class AbstractDbAdapter implements IDbAdapter
{
	/**
	 * @var array
	 */
	private $config;
	/**
	 * @var \PDO
	 */
	private $pdo;


	/**
	 * AbstractDbAdapter constructor.
	 * @param array $config
	 */
	public function __construct($config)
	{
		$this->config = $config;
		$this->init();
	}

	function init(): IDbAdapter
	{
		return $this->connect();
	}

	/**
	 * @inheritdoc
	 */
	function connect(): IDbAdapter
	{
		if ($this->pdo == null) {
			try {
				$this->pdo = new \PDO($this->config[Db::CONNECTION_STRING]);
			} catch (\PDOException $e) {
				echo "error with db!" . PHP_EOL . $e->getMessage();
				exit;
			}
		}
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	function fetchRows(string $query, array $parameters = null, string $class = null): array
	{
		$statement   = $this->pdo->prepare($query);
		$fetchResult = null;
		if ($statement->execute(array_values($parameters))) {
			/**
			 * TODO: take this to separate insert function
			 */
			if (stripos($query, QueryType::INSERT) === 0) {
				return [$this->pdo->lastInsertId()]; //to keep method returning value
			}
			$result = [];
			while ($fetchResult = $class ? $statement->fetchObject($class) : $statement->fetch()) {
				$result[] = $fetchResult;
			}
			return $result;
		}
		throw new \Exception(vsprintf("Error with DB: %s %s:  %s", $statement->errorInfo()));
	}

	/**
	 * @inheritdoc
	 */
	function executeQuery(string $query, array $parameters = null): bool
	{
		try {
			$statement = $this->pdo->prepare($query);
			return $statement ? $statement->execute($parameters) : false;
		} catch (\Error $e) {
			return false;
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * @inheritdoc bool
	 */
	public function beginTransaction():bool
	{
		return $this->pdo->beginTransaction();
	}

	/**
	 * @inheritdoc bool
	 */
	public function commit(): bool
	{
		return $this->pdo->commit();
	}

	/**
	 * @inheritdoc
	 */
	public function rollBack(): bool
	{
		return $this->pdo->rollBack();

	}

	/**
	 * @inheritdoc
	 */
	abstract public function createTableQuery(DbModelConfig $config): string;

	/**
	 * @inheritdoc
	 */
	abstract public function dropTableQuery(DbModelConfig $config, $cascade = true, $ifExists = true): string;

	/**
	 * @inheritDoc
	 */
	abstract function parseParameters(array $conditions): array;

	/**
	 * @inheritdoc
	 */
	abstract protected function extractDbColumnDefinition(DbModelColumnConfig $dbColumnConfig): string;

}