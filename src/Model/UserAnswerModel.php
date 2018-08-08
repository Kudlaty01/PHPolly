<?php

namespace Model;

use Enum\Model\Config\ColumnType;
use Enum\Model\RelationType;
use Model\Config\AbstractDbModel;
use Model\Config\Db\DbModelColumnConfig as ColumnConfig;
use Model\Config\Db\DbModelConfig as ModelConfig;
use Model\Config\Db\DbModelRelationConfig as RelationConfig;

/**
 * Class UserAnswerModel
 *
 * @package \Model
 */
class UserAnswerModel extends AbstractDbModel implements IDbModel
{
	/**
	 * @var int
	 */
	protected $ua_user_id;
	/**
	 * @var int
	 */
	protected $ua_answer_id;
	/**
	 * @var UserModel
	 */
	protected $user;
	/**
	 * @var AnswerModel
	 */
	protected $answer;

	/**
	 * Configuration to be used in repository
	 * @param bool $withRelations to prevent circular references
	 * @return ModelConfig
	 */
	static function getConfig(bool $withRelations = true): ModelConfig
	{
		$dbModelConfig = (new ModelConfig())
			->setTable('user_answer')
			->setColumns([
				'ua_user_id'   => new ColumnConfig(ColumnType::INTEGER),
				'ua_answer_id' => new ColumnConfig(ColumnType::INTEGER),
			])
			->setPrimaryKey('ua_user_id, ua_answer_id');
		if ($withRelations) {
			$dbModelConfig
				->setRelations([
					'user'   => new RelationConfig(RelationType::MANY_TO_ONE, UserModel::getConfig(false), 'ua_user_id'),
					'answer' => new RelationConfig(RelationType::MANY_TO_ONE, UserModel::getConfig(false), 'ua_answer_id'),
				]);
		}
		return $dbModelConfig;
	}

	/**
	 * @return UserModel
	 */
	public function getUser(): UserModel
	{
		return $this->user;
	}

	/**
	 * @param UserModel $user
	 * @return UserAnswerModel
	 */
	public function setUser(UserModel $user): UserAnswerModel
	{
		$this->user = $user;
		return $this;
	}

	/**
	 * @return AnswerModel
	 */
	public function getAnswer(): AnswerModel
	{
		return $this->answer;
	}

	/**
	 * @param AnswerModel $answer
	 * @return UserAnswerModel
	 */
	public function setAnswer(AnswerModel $answer): UserAnswerModel
	{
		$this->answer = $answer;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getUaUserId(): int
	{
		return $this->ua_user_id;
	}

	/**
	 * @param int $ua_user_id
	 * @return UserAnswerModel
	 */
	public function setUaUserId(int $ua_user_id): UserAnswerModel
	{
		$this->ua_user_id = $ua_user_id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getUaAnswerId(): int
	{
		return $this->ua_answer_id;
	}

	/**
	 * @param int $ua_answer_id
	 * @return UserAnswerModel
	 */
	public function setUaAnswerId(int $ua_answer_id): UserAnswerModel
	{
		$this->ua_answer_id = $ua_answer_id;
		return $this;
	}

}