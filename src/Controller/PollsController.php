<?php

namespace Controller;

use Enum\Config\Model;
use Enum\ErrorMessages;
use Model\AnswerModel;
use Model\PollModel;
use Tools\AbstractController;
use Tools\IController;
use Tools\JsonResult;
use Tools\ViewResult;

/**
 * Class PollsController
 *
 * @package \Controller
 */
class PollsController extends AbstractController implements IController
{

	/**
	 * @inheritdoc
	 */
	public function getScripts(): array
	{
		return [
			'/public/js/polls.js',
		];
	}

	/**
	 * Main view for the grid to appear
	 * @return ViewResult
	 */
	public function indexAction(): ViewResult
	{
		$this->checkLogin(true);
		$data = ['msg' => ''];
		return new ViewResult($data, 'Polls list');
	}

	/**
	 * dynamically presented list of polls
	 * @return JsonResult
	 */
	public function listAction(): JsonResult
	{
		if ($this->getRequest()->isPost()) {
			$limit     = $this->getRequest()->getPost('limit', 100);
			$offset    = $this->getRequest()->getPost('offset', 0);
			$pollList  = $this->modelRepository->list(new PollModel(), null, null, $limit, $offset);
			$pollCount = $this->modelRepository->list(new PollModel(), null, ['count' => 'COUNT(*)'], 0);
			$plainList = array_map(function (PollModel $pollItem) {
				return $pollItem->toArray();
			}, $pollList);
			return new JsonResult([
				'data'  => $plainList,
				// I know this is a dirty hack and would improve it later on
				'count' => $pollCount[0]->count,
			]);
		} else {
			return new JsonResult(['message' => ErrorMessages::WRONG_REQUEST_TYPE]);
		}
	}

	/**
	 * new poll is created here. View and JS code namespace are shared with edit action
	 * @return ViewResult
	 */
	public function addAction(): ViewResult
	{
		$data = ['question' => '', 'expirationDate' => (new \DateTime())->add(\DateInterval::createFromDateString('+1 week'))->format(Model::DATE_TIME_FORMAT)];
		if ($this->getRequest()->isPost()) {
			$data  = $this->getRequest()->getPost();
			$model = new PollModel();
			$model
				->setQuestion($data['question'])
				->setExpirationDate($data['expirationDate']);
			$this->modelRepository->beginTransaction();
			try {
				$savedPoll = $this->modelRepository->add($model);
				foreach ($data['answers'] as $answerData) {
					$answer = new AnswerModel();
					$answer->setAPollId($savedPoll->getId())
						->setText($answerData['text']);
					$this->modelRepository->add($answer);
				}
				$this->modelRepository->commit();
				$this->getRequest()->redirect('/polls');
			} catch (\Exception $e) {
				$this->modelRepository->rollBack();
			}

		}
		return new ViewResult(['action' => 'add', 'pollData' => json_encode($data)], 'Add new poll', 'polls/edit');

	}

	/**
	 * Edition action for polls
	 * @return ViewResult
	 */
	public function editAction(): ViewResult
	{
		$id                  = $this->getRequest()->getParam('id');
		$poll                = $this->modelRepository->find(new PollModel(), $id, ['answers']);
		$answers             = $this->modelRepository->list(new AnswerModel(), ['a_poll_id' => $id]);
		$pollData            = $poll->toArray();
		$pollData['answers'] = array_map(function (AnswerModel $answer) {
			return $answer->toArray();
		}, $answers);
		if ($this->getRequest()->isPost()) {
			/**
			 * I know, should have been more elegant
			 */
			$data           = $this->getRequest()->getPost();
			$pollChanges    = array_intersect_key($data, array_flip(['question', 'expirationDate']));
			$answersChanges = $data['answers'];
			$this->modelRepository->beginTransaction();
			try {
				$this->modelRepository->update($poll, $pollChanges);
				foreach ($answersChanges as $index => $change) {
					$answerId = $change['answer_id'];
					$text     = $change['text'];
					if ($answerId) {
						$this->modelRepository->update((new AnswerModel())->setAnswerId($answerId), ['text' => $text]);
					} else {
						$newAnswer = new AnswerModel();
						$newAnswer->setAPollId($id)
							->setText($text);
						$savedAnswer                                 = $this->modelRepository->add($newAnswer);
						$pollChanges['answers'][$index]['answer_id'] = $savedAnswer->getId();
					}
				}
				$this->modelRepository->commit();
				$this->getRequest()->redirect('/polls');
			} catch (\Exception $e) {
				$this->modelRepository->rollBack();
				$pollData            = array_merge($pollData, $pollChanges);
				$pollData['answers'] = $answersChanges;
			}
		}
		return new ViewResult(['action' => "$id", 'pollData' => json_encode($pollData)], 'Edit poll #' . $id);
	}

	/**
	 * Polls removal action. Available only by POST
	 * TODO: apply also ajax request type verification
	 * @return JsonResult
	 */
	public function removeAction(): JsonResult
	{
		if ($this->getRequest()->isPost()) {
			/**
			 * TODO: check if object really exists
			 */
			$model = new PollModel();
			$model->setPollId($this->getRequest()->getPost('id'));
			$result       = $this->modelRepository->delete($model);
			$responseData = [
				'success' => $result,
				'message' => $result ? 'item deleted!' : 'an error occurred',
			];
			return new JsonResult($responseData);
		} else {
			return new JsonResult(['message' => ErrorMessages::WRONG_REQUEST_TYPE]);
		}

	}

}