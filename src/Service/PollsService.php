<?php

namespace Service;

use Model\PollModel;
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
	 * @return PollModel
	 */
	public function getPollForUser(int $userId): PollModel
	{


	}


	public function addPoll(PollModel $model)
	{

	}

	public function updatePoll(PollModel $model, $changes)
	{

	}

	public function deletePoll(PollModel $model)
	{

	}

	public function isPollExpired(PollModel $model):bool
	{
		$date = new \DateTime($model->getExpirationDate());
		return $date < new \DateTime();

	}

	public function getTotalVotes(int $id): int
	{

	}
}
