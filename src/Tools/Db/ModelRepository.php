<?php

namespace Tools\Db;

use Enum\Model\RelationType;
use Enum\QueryType;
use Model\Config\Db\DbModelConfig;
use Model\Config\Db\DbModelRelationConfig;
use Model\IDbModel;

/**
 * Class ModelRepository
 *
 * @package \Tools\Db
 */
class ModelRepository
{
	/**
	 * @var string
	 */
	private $class;

	/**
	 * @var IDbAdapter
	 */
	private $dbEngine;

	/**
	 * ModelRepository constructor.
	 * @param IDbAdapter $dbEngine
	 */
	public function __construct(IDbAdapter $dbEngine)
	{
		$this->dbEngine = $dbEngine;
	}


	/**
	 * @param DbModelConfig $modelConfig
	 */
	public function install(DbModelConfig $modelConfig)
	{
		$query = $this->dbEngine->dropTableQuery($modelConfig);
		$this->dbEngine->executeQuery($query);
		$query = $this->dbEngine->createTableQuery($modelConfig);
		$this->dbEngine->executeQuery($query);

	}

	/**
	 * @param IDbModel $model may be empty - useful for fetchObject and config getting, just ton't want to use string class name
	 * @param int      $id
	 * @param array    $relations array of relations to be fetched with the entity
	 * @return IDbModel
	 */
	public function find(IDbModel $model, int $id, array $relations = null):IDbModel
	{
		$modelConfig = $model::getConfig();
		$query       = new Query($this->dbEngine, QueryType::SELECT, $modelConfig->getTable());
		$primaryKey  = $modelConfig->getPrimaryKey();
		$query->setConditions([$primaryKey . '= ?']);
		if (!empty($relations)) {
			// Check if all relations exist in model config relations
			if (empty(array_diff($relations, array_keys($model::getConfig()->getRelations())))) {
				$queriedRelationsMeta = array_intersect_key($model::getConfig()->getRelations(), array_flip($relations));
				$joinTables           = array_map(function (DbModelRelationConfig $relationMetadata) use ($primaryKey) {
					$joinKeys = [
						RelationType::ONE_TO_MANY => $primaryKey,
						RelationType::MANY_TO_ONE => $relationMetadata->getTargetConfiguration()->getPrimaryKey(),
					];
					return sprintf("%s ON %s=%s", $relationMetadata->getTargetConfiguration()->getTable(), $relationMetadata->getId(), $joinKeys[$relationMetadata->getRelationType()]);
				}, $queriedRelationsMeta);
				$query->setLeftOuterJoinTables($joinTables);
			} else {
				//assume all $relations are ready join statements
				$query->setLeftOuterJoinTables($relations);
			}
		}
		$result = $this->dbEngine->fetchRows($query, [$id], get_class($model));
		return $result[0];
	}

	/**
	 * @param IDbModel $model
	 * @return IDbModel
	 */
	public
	function add(IDbModel $model): IDbModel
	{
		$dbModelConfig = $model::getConfig();
		$query         = new Query($this->dbEngine, QueryType::INSERT, $dbModelConfig->getTable());
		$primaryKey    = $dbModelConfig->getPrimaryKey();
		$parameters    = $model->toArray(true);
		$query->setParameters(array_fill_keys(array_keys($parameters), '?'));
		$result = $this->dbEngine->fetchRows($query, array_values($parameters));
		$model->setColumnValue($primaryKey, $result[0]);
		return $model;
	}

	/**
	 * @param IDbModel $model
	 * @param array    $changes
	 * @return IDbModel
	 */
	public
	function update(IDbModel $model, array $changes): IDbModel
	{
		foreach ($changes as $columnName => $change) {
			$model->setColumnValue($columnName, $change);
		}
		$dbModelConfig = $model::getConfig();
		$primaryKey    = $dbModelConfig->getPrimaryKey();
		$query         = new Query($this->dbEngine, QueryType::UPDATE, $dbModelConfig->getTable());
		$query->setParameters(array_fill_keys(array_keys($changes), '?'))
			->setConditions([$primaryKey . '= ?']);
		$changes[] = $model->getId();
		if ($this->dbEngine->executeQuery($query, array_values($changes))) {
			foreach ($changes as $index => $change) {
				$model->setColumnValue($index, $change);
			}
			return $model;
		}
		return null;
	}

	/**
	 * @param IDbModel $model
	 * @return bool
	 */
	public
	function delete(IDbModel $model)
	{
		$dbModelConfig = $model::getConfig();
		$query         = new Query($this->dbEngine, QueryType::DELETE, $dbModelConfig->getTable());
		$primaryKey    = $dbModelConfig->getPrimaryKey();
		$query->setConditions([$primaryKey . '= ?']);
		$result = $this->dbEngine->executeQuery($query, [$model->getId()]);
		return $result;
	}

	public function findOneBy(IDbModel $model, array $conditions = null)
	{
		$result = $this->list($model, $conditions);
		if (count($result) > 1) {
			throw new \Exception("More than one records found for given conditions!");
		} else if (empty($result)) {
			throw new \Exception("No records found for given conditions!");
		} else {
			return $result[0];
		}

	}

	/**
	 * @param IDbModel   $model may be empty - placeholder of class name and config
	 * @param array|null $conditions
	 * @param null       $fields
	 * @param int        $limit
	 * @param int        $offset
	 * @return array
	 */
	public
	function list(IDbModel $model, array $conditions = null, $fields = null, $limit = 100, $offset = 0): array
	{
		$modelConfig = $model::getConfig();
		$query       = new Query($this->dbEngine, QueryType::SELECT, $modelConfig->getTable());
		if (!empty($conditions)) {
			$query->setConditions(array_fill_keys(array_keys($conditions), '?'));
		}
		if (!empty($fields)) {
			$query->setFields($fields);
		}
		if ($limit) {
			$query->setLimit($limit);
		}
		if ($offset) {
			$query->setOffset($offset);
		}

//		$query->setConditions($this->dbEngine->parseParameters($conditions));
		$result = $this->dbEngine->fetchRows($query, $conditions, get_class($model));
		return $result;
	}

	/**
	 * @return bool
	 */
	public function beginTransaction(): bool
	{
		return $this->dbEngine->beginTransaction();
	}

	/**
	 * @return bool
	 */
	public function commit(): bool
	{
		return $this->dbEngine->commit();
	}

	public function rollBack(): bool
	{
		return $this->dbEngine->rollBack();
	}

	/**
	 * Copied from dbEngine
	 * @param string $query
	 * @param array  $parameters
	 * @param string $class
	 * @return mixed
	 */
	public function fetchRows(string $query, array $parameters = null, string $class = null)
	{
		return $this->dbEngine->fetchRows($query, $parameters, $class);
	}



}