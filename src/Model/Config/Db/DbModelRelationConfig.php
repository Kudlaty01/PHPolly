<?php

namespace Model\Config\Db;

/**
 * Class DbModelRelationConfig
 * @package Model\Config\Db
 */
class DbModelRelationConfig
{
	/**
	 * @var string
	 */
	private $relationType;
	/**
	 * @var DbModelConfig
	 */
	private $class;
	/**
	 * @var string
	 */
	private $id;

	/**
	 * DbModelRelationConfig constructor.
	 * @param string        $relationType
	 * @param DbModelConfig $class
	 * @param string        $id
	 */
	public function __construct(string $relationType, DbModelConfig $class, $id)
	{

		$this->relationType = $relationType;
		$this->class = $class;
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getRelationType(): string
	{
		return $this->relationType;
	}

	/**
	 * @param string $relationType
	 * @return DbModelRelationConfig
	 */
	public function setRelationType(string $relationType): DbModelRelationConfig
	{
		$this->relationType = $relationType;
		return $this;
	}

	/**
	 * @return DbModelConfig
	 */
	public function getTargetConfiguration(): DbModelConfig
	{
		return $this->class;
	}

	/**
	 * @param DbModelConfig $class
	 * @return DbModelRelationConfig
	 */
	public function setClass(DbModelConfig $class): DbModelRelationConfig
	{
		$this->class = $class;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 * @return DbModelRelationConfig
	 */
	public function setId(string $id): DbModelRelationConfig
	{
		$this->id = $id;
		return $this;
	}


}