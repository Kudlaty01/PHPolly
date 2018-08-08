<?php

namespace Model;

use Enum\Model\Config\ColumnType;
use Enum\Model\RelationType;
use Model\Config\AbstractDbModel;
use Model\Config\Db\DbModelColumnConfig as ColumnConfig;
use Model\Config\Db\DbModelConfig as ModelConfig;
use Model\Config\Db\DbModelRelationConfig as RelationConfig;

/**
 * Class UserModel
 *
 * @package \Model
 */
class UserModel extends AbstractDbModel implements IDbModel
{

	/**
	 * @var int
	 */
	protected $user_id;
	/**
	 * @var string
	 */
	protected $login;
	/**
	 * @var string
	 */
	protected $passwordHash;
	/**
	 * @var int
	 */
	protected $backendUser;
	/**
	 * @var AnswerModel[]
	 */
	protected $answers;

	/**
	 * UserModel constructor.
	 */
	public function __construct()
	{
		$this->answers = [];
	}


	/**
	 * custom db config for model
	 * @param bool $withRelations
	 * @return ModelConfig
	 */
	static function getConfig(bool $withRelations = true): ModelConfig
	{
		$dbModelConfig = (new ModelConfig())
			->setTable('users')
			->setColumns([
				'user_id'      => new ColumnConfig(ColumnType::INTEGER),
				'login'        => (new ColumnConfig(ColumnType::VARCHAR))->setLength(20),
				'passwordHash' => (new ColumnConfig(ColumnType::VARCHAR))->setLength(60),
				'backendUser'  => new ColumnConfig(ColumnType::INTEGER), //since sqlite doesn't support booleans
			])
			->setPrimaryKey('user_id');
		if ($withRelations) {
			$dbModelConfig
				->setRelations([
					/**
					 * No time to implement MANY_TO_MANY
					 */
					'answers' => new RelationConfig(RelationType::ONE_TO_MANY, UserAnswerModel::getConfig(false), 'ua_user_id'),
				]);
		}
		return $dbModelConfig;
	}

	/**
	 * @return int
	 */
	public function getUserId(): int
	{
		return $this->user_id;
	}

	/**
	 * @param int $user_id
	 * @return UserModel
	 */
	public function setUserId(int $user_id): UserModel
	{
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLogin(): string
	{
		return $this->login;
	}

	/**
	 * @param string $login
	 * @return UserModel
	 */
	public function setLogin(string $login): UserModel
	{
		$this->login = $login;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPasswordHash(): string
	{
		return $this->passwordHash;
	}

	/**
	 * @param string $passwordHash
	 * @return UserModel
	 */
	public function setPasswordHash(string $passwordHash): UserModel
	{
		$this->passwordHash = $passwordHash;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isBackendUser(): bool
	{
		return $this->backendUser;
	}

	/**
	 * @param boolean $backendUser
	 * @return UserModel
	 */
	public function setBackendUser(bool $backendUser): UserModel
	{
		$this->backendUser = $backendUser;
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
	 * @return UserModel
	 */
	public function setAnswers(array $answers): UserModel
	{
		$this->answers = $answers;
		return $this;
	}

}