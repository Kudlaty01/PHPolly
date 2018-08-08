<?php

namespace Model;

use Enum\Model\Config\ColumnType;
use Enum\Model\RelationType;
use Model\Config\AbstractDbModel;
use Model\Config\Db\DbModelColumnConfig as ColumnConfig;
use Model\Config\Db\DbModelConfig as ModelConfig;
use Model\Config\Db\DbModelRelationConfig as RelationConfig;

/**
 * Class PollModel
 *
 * @package \Model
 */
class PollModel extends AbstractDbModel implements IDbModel
{
	/**
	 * @var int
	 */
	protected $poll_id;
	/**
	 * @var string
	 */
	protected $question;
	/**
	 * @var string
	 */
	protected $expirationDate;
	/**
	 * @var AnswerModel[]
	 */
	protected $answers;

	/**
	 * PollModel constructor.
	 */
	public function __construct()
	{
		$this->answers = [];
	}


	/**
	 * Configuration to be used in repository
	 * @param bool $withRelations
	 * @return ModelConfig
	 */
	static function getConfig(bool $withRelations = true): ModelConfig
	{
		$dbModelConfig = (new ModelConfig())
			->setTable('polls')
			->setColumns([
				'poll_id'        => new ColumnConfig(ColumnType::INTEGER),
				'question'       => new ColumnConfig(ColumnType::VARCHAR),
				/**
				 * SQLite does not support dates
				 */
				'expirationDate' => (new ColumnConfig(ColumnType::VARCHAR))->setNull(true),
			])
			->setPrimaryKey('poll_id');
		if ($withRelations) {
			$dbModelConfig
				->setRelations([
					'answers' => new RelationConfig(RelationType::ONE_TO_MANY, AnswerModel::getConfig(false), 'a_poll_id'),
				]);
		}
		return $dbModelConfig;
	}

	/**
	 * @return int
	 */
	public function getPollId(): int
	{
		return $this->poll_id;
	}

	/**
	 * @param int $poll_id
	 * @return PollModel
	 */
	public function setPollId(int $poll_id): PollModel
	{
		$this->poll_id = $poll_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getQuestion(): string
	{
		return $this->question;
	}

	/**
	 * @param string $question
	 * @return PollModel
	 */
	public function setQuestion(string $question): PollModel
	{
		$this->question = $question;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getExpirationDate(): string
	{
		return $this->expirationDate;
	}

	/**
	 * @param string|null $expirationDate
	 * @return PollModel
	 */
	public function setExpirationDate($expirationDate): PollModel
	{
		$this->expirationDate = $expirationDate;
		return $this;
	}

	/**
	 * @return AnswerModel[]
	 */
	public function getAnswers(): array
	{
		return $this->answers;
	}

	/**
	 * @param AnswerModel[] $answers
	 * @return PollModel
	 */
	public function setAnswers(array $answers): PollModel
	{
		$this->answers = $answers;
		return $this;
	}


}