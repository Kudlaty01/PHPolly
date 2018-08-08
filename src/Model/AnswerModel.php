<?php

namespace Model;

use Enum\Model\Config\ColumnType;
use Enum\Model\RelationType;
use Model\Config\AbstractDbModel;
use Model\Config\Db\DbModelColumnConfig as ColumnConfig;
use Model\Config\Db\DbModelConfig as ModelConfig;
use Model\Config\Db\DbModelRelationConfig as RelationConfig;

/**
 * Class AnswerModel
 *
 * @package \Model
 */
class AnswerModel extends AbstractDbModel implements IDbModel
{
	/**
	 * @var int
	 */
	protected $answer_id;
	/**
	 * @var int
	 */
	protected $a_poll_id;
	/**
	 * @var PollModel
	 */
	protected $poll;
	/**
	 * @var string
	 */
	protected $text;

	/**
	 * Configuration to be used in repository
	 * @param bool $withRelations
	 * @return ModelConfig
	 */
	static function getConfig(bool $withRelations = true): ModelConfig
	{
		$dbModelConfig = (new ModelConfig())
			->setTable('answers')
			->setColumns([
				'answer_id' => new ColumnConfig(ColumnType::INTEGER),
				'a_poll_id' => new ColumnConfig(ColumnType::INTEGER),
				'text'      => (new ColumnConfig(ColumnType::VARCHAR))->setLength(40),
			])
			->setPrimaryKey('answer_id');
		if ($withRelations) {
			$dbModelConfig
				->setRelations([
					'poll' => new RelationConfig(RelationType::MANY_TO_ONE, PollModel::getConfig(false), 'a_poll_id'),
				]);
		}
		return $dbModelConfig;
	}

	/**
	 * @return int
	 */
	public function getAnswerId(): int
	{
		return $this->answer_id;
	}

	/**
	 * @param int $answer_id
	 * @return AnswerModel
	 */
	public function setAnswerId(int $answer_id): AnswerModel
	{
		$this->answer_id = $answer_id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAPollId(): int
	{
		return $this->a_poll_id;
	}

	/**
	 * @param int $a_poll_id
	 * @return AnswerModel
	 */
	public function setAPollId(int $a_poll_id): AnswerModel
	{
		$this->a_poll_id = $a_poll_id;
		return $this;
	}

	/**
	 * @return PollModel
	 */
	public function getPoll(): PollModel
	{
		return $this->poll;
	}

	/**
	 * @param PollModel $poll
	 * @return AnswerModel
	 */
	public function setPoll(PollModel $poll): AnswerModel
	{
		$this->poll = $poll;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getText(): string
	{
		return $this->text;
	}

	/**
	 * @param string $text
	 * @return AnswerModel
	 */
	public function setText(string $text): AnswerModel
	{
		$this->text = $text;
		return $this;
	}


}