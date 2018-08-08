<?php

namespace Model\Config\Db;

use Enum\Model\Config\ColumnType;

/**
 * Class DbModelColumnConfig
 *
 * @package \Model\Config\Db
 */
class DbModelColumnConfig implements IDbModelConfig
{

	/**
	 * @var string
	 */
	private $type;
	/**
	 * TODO: Further release may have this parameter exclusive for specific column types deriving from general column config model
	 * @var int
	 */
	private $length;
	/**
	 * @var bool
	 */
	private $null;

	/**
	 * DbModelColumnConfig constructor.
	 * @param $type
	 */
	public function __construct($type)
	{
		$this->type = $type;
	}

	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param mixed $type
	 * @return DbModelColumnConfig
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLength()
	{
		return $this->length;
	}

	/**
	 * @param mixed $length
	 * @return DbModelColumnConfig
	 */
	public function setLength($length)
	{
		$this->length = $length;
		return $this;
	}


	/**
	 * converts config class to an array for further parsing
	 * @return array
	 */
	function toArray()
	{
		$result = ['type' => $this->type];
		if ($this->null !== null) {
			$result += ['null' => $this->null];
		}
		switch ($this->type) {
			case ColumnType::VARCHAR:
				if ($this->length) {
					$result += ['length' => $this->length];
				}
				break;
		}
		return $result;
	}

	/**
	 * @return bool
	 */
	public function getNull()
	{
		return $this->null;
	}

	/**
	 * @param bool $null
	 * @return DbModelColumnConfig
	 */
	public function setNull($null)
	{
		$this->null = $null;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	function __toString()
	{
		$result = $this->type;
		switch ($this->type) {
			case ColumnType::VARCHAR:
				if ($this->length) {
					$result .= ' ' . $this->length;
				}
				break;
		}
		if ($this->null !== null) {
			$result .= ' ' . $this->null ? 'NULL' : 'NOT NULL';
		}
		return $result;
	}


}