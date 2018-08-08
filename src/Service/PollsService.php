<?php

namespace Service;

use Enum\Config\Model;
use Model\AnswerModel;
use Model\PollModel;
use Model\UserAnswerModel;
use Tools\Db\ModelRepository;

/**
 * Class PollsService
 * TODO: move from disastrous Controller actions to here
 *
 * @package \Service
 */
class PollsService
{
	/**
	 * @var ModelRepository
	 */
	private $modelRepository;


	/**
	 * PollsService constructor.
	 * @param ModelRepository $modelRepository
	 */
	public function __construct(ModelRepository $modelRepository)
	{
		$this->modelRepository = $modelRepository;
	}


	/**
	 * @param int $userId
	 * @return PollModel:null
	 */
	public function getPollForUser(int $userId)
	{
		/**
		 * TODO: perform a query to fetch polls not expired nor answered by user
		 */
		/** @var PollModel $poll */
		$pollResult = $this->modelRepository->fetchRows("SELECT p.* FROM polls p
LEFT OUTER JOIN (
                  SELECT a_poll_id AS vote_poll_id
                  FROM answers aa
                    JOIN user_answer ON ua_answer_id = answer_id
                  WHERE ua_user_id = ?
                ) voted_polls
  ON poll_id=vote_poll_id
  WHERE vote_poll_id IS NULL AND p.expirationDate > ?
ORDER BY RANDOM() LIMIT 1", [$userId, (new \DateTime())->format(Model::DATE_TIME_FORMAT)], PollModel::class);
		if ($pollResult) {
			$poll    = $pollResult[0];
			$answers = $this->modelRepository->list(new AnswerModel(), ['a_poll_id' => $poll->getId()]);
			$poll->setAnswers($answers);
			return $poll;
		}
		return null;
//		$this->modelRepository->findOneBy($model);


	}

	/**
	 * Check whether the poll has already expired
	 * @param PollModel $model
	 * @return bool
	 */
	public function isPollExpired(PollModel $model):bool
	{
		$expirationDate = $model->getExpirationDate();
		if (!$expirationDate) {
			return false;
		}
		$date = new \DateTime($expirationDate);
		return $date < new \DateTime();

	}

	/**
	 * get total votes for
	 * @param int $pollId
	 * @return array an associative array of answerId => totalVotes
	 */
	public function getTotalVotes(int $pollId): array
	{
		$totals = $this->modelRepository->fetchRows("SELECT answer_id,count(ua_user_id) AS total
				  FROM answers
				LEFT OUTER JOIN user_answer ON answer_id=ua_answer_id
				WHERE a_poll_id=?
				GROUP BY answer_id", [$pollId]);
		return array_column($totals, 'total', 'answer_id');

	}

	/**
	 * @param $answerId
	 * @param $userId
	 * @return bool
	 */
	public function voteInPoll($answerId, $userId): bool
	{
		$voteModel = new UserAnswerModel();
		$voteModel->setUaAnswerId($answerId)
			->setUaUserId($userId);
		$result = $this->modelRepository->add($voteModel);
		return $result !== null;
	}

	/**
	 * @param $pollId
	 * @param $userId
	 * @return bool
	 */
	public function userAlreadyVoted($pollId, $userId): bool
	{
		$result = $this->modelRepository->fetchRows("SELECT count(*) FROM
			answers
			JOIN user_answer ON answer_id=ua_answer_id
			WHERE a_poll_id=? AND ua_user_id=?", [$pollId, $userId]);
		return $result[0][0] > 0;

	}
}
