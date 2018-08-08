<?php

namespace Controller;

use Service\InstallationService;
use Service\PollsService;
use Tools\AbstractController;
use Tools\IController;
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
	public function indexAction()
	{
		$this->checkLogin();
		$msg = "Welcome to the polling application!";
		return new ViewResult(['msg' => $msg]);
	}

	/**
	 * Installation. Available only if no db file is present or for backend users
	 * @return ViewResult
	 */
	public function installAction()
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

		return new ViewResult(['msg' => 'Install all required db tables!']);
	}

	/**
	 * @return ViewResult
	 */
	public function loginAction()
	{
		$model = array_fill_keys(['login', 'password'], '');
		if ($this->getRequest()->isPost()) {
			$model['login']    = $this->getRequest()->getPost('login');
			$model['password'] = $this->getRequest()->getPost('password');
			if ($this->authenticationService->login($model['login'], $model['password'])) {
				$this->getRequest()->redirect($this->sessionManager->isBackendUser() ? '/polls' : '/index');
			}
		}
		$model['password'] = '';
		return new ViewResult($model);
	}

	/**
	 * Say goodbye
	 */
	public function logoutAction()
	{
		$this->sessionManager->logout();
		$this->getRequest()->redirect('/login');
	}

}
