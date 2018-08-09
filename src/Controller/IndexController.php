<?php

namespace Controller;

use Enum\ErrorMessages;
use Model\AnswerModel;
use Model\PollModel;
use Service\InstallationService;
use Service\PollsService;
use Tools\AbstractController;
use Tools\Exception\NoRecordFoundException;
use Tools\IController;
use Tools\JsonResult;
use Tools\ViewResult;

class IndexController extends AbstractController implements IController
{
	/**
	 * @var InstallationService
	 */
	private $installationService;
	/**
	 * @var PollsService
	 */
	private $pollsService;

	/**
	 * @inheritDoc
	 */
	function getScripts(): array
	{
		return [
			'/public/js/frontend.js',
		];
	}


	/**
	 * IndexController constructor.
	 * @param InstallationService $installationService
	 * @param PollsService        $pollsService
	 */
	public function __construct(InstallationService $installationService, PollsService $pollsService)
	{
		$this->installationService = $installationService;
		$this->pollsService = $pollsService;
	}

	/**
	 * Default action for frontend users to start on when signed in
	 * @return ViewResult
	 */
	public function indexAction(): ViewResult
	{
		$this->checkLogin();
		$msg = "Welcome to the polling application!";
		return new ViewResult(['msg' => $msg], "Answer poll");
	}

	/**
	 * Installation. Available only if no db file is present or for backend users
	 * @return ViewResult
	 */
	public function installAction(): ViewResult
	{
		if ($this->installationService->installed() && !$this->sessionManager->isBackendUser()) {
			$this->getRequest()->redirect('/login');
		}
		if ($this->getRequest()->isPost()) {
			$result = $this->installationService->install();
			if ($result) {
				$this->getRequest()->redirect('/login');
			}
		}

		return new ViewResult(['msg' => 'Install all required db tables! Don\'t forget to run "yarn run setup" in the console before first usage!'], "Installation");
	}

	/**
	 * @return ViewResult
	 */
	public function loginAction(): ViewResult
	{
		$model = array_fill_keys(['login', 'password', 'error'], '');
		if ($this->getRequest()->isPost()) {
			$model['login']    = $this->getRequest()->getPost('login');
			$model['password'] = $this->getRequest()->getPost('password');
			try {
				if ($this->authenticationService->login($model['login'], $model['password'])) {
					$this->getRequest()->redirect($this->sessionManager->isBackendUser() ? '/polls' : '/index');
				} else {
					$model['error'] = "Login and password do not match!";
				}
			} catch (NoRecordFoundException $e) {
				$model['error'] = "No account with provided login found!";
			}
		}
		$model['password'] = '';
		return (new ViewResult($model, "Sign in"));
	}

	/**
	 * Say goodbye
	 */
	public function logoutAction()
	{
		$this->sessionManager->logout();
		$this->getRequest()->redirect('/login');
	}

	public function getPollAction(): JsonResult
	{
		$pollData = null;
		$message  = '';
		if ($this->getRequest()->isPost()) {
			$poll = $this->pollsService->getPollForUser($this->sessionManager->getUserId());
			if ($poll) {
				$pollData            = $poll->toArray();
				$pollData['answers'] = array_map(function (AnswerModel $answer) {
					return $answer->toArray();
				}, $poll->getAnswers());
			} else {
				$message = ErrorMessages::NO_MORE_POLLS;
			}
		} else {
			$message = ErrorMessages::WRONG_REQUEST_TYPE;
		}
		return new JsonResult(['poll' => $pollData, 'error' => $message]);
	}

	/**
	 * Vote in poll
	 * TODO: Check if answer is for poll
	 * @return JsonResult
	 */
	public function voteAction(): JsonResult
	{
		$message = '';
		$totals  = null;
		if ($this->getRequest()->isPost()) {
			$pollId    = $this->getRequest()->getPost('poll_id');
			$answerId  = $this->getRequest()->getPost('answer_id');
			$pollModel = $this->modelRepository->find(new PollModel(), $pollId);
			$userId    = $this->sessionManager->getUserId();

			if ($this->pollsService->isPollExpired($pollModel)) {
				$message = ErrorMessages::POLL_EXPIRED;
			} else if ($this->pollsService->userAlreadyVoted($pollId, $userId)) {
				$message = ErrorMessages::USER_ALREADY_VOTED;
			} else {
				if ($this->pollsService->voteInPoll($answerId, $userId)) {
					$totals = $this->pollsService->getTotalVotes($pollId);
				} else {
					$message = ErrorMessages::DATABASE_ERROR;
				}
			}
		} else {
			$message = ErrorMessages::WRONG_REQUEST_TYPE;
		}
		return new JsonResult(['totals' => $totals, 'error' => $message]);
	}

}
