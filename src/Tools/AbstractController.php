<?php

namespace Tools;

use Tools\AwareInterface\IAuthenticationServiceAware;
use Tools\AwareInterface\IDependencyRegistrarAware;
use Tools\AwareInterface\IModelRepositoryAware;
use Tools\AwareInterface\ISessionManagerAware;
use Tools\Db\ModelRepository;
use Tools\Http\Request;

/**
 * Class AbstractController
 *
 * @package \Controller\Tools
 */
class AbstractController implements IController, IDependencyRegistrarAware, IModelRepositoryAware, ISessionManagerAware, IAuthenticationServiceAware
{
	/**
	 * @var DependencyRegistrar
	 */
	protected $dependencyRegistrar;
	/**
	 * @var ModelRepository
	 */
	protected $modelRepository;
	/**
	 * @var SessionManager
	 */
	protected $sessionManager;
	/**
	 * @var AuthenticationService
	 */
	protected $authenticationService;
	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @inheritDoc
	 */
	function getScripts(): array
	{
		return [];
	}

	/**
	 * @return Request
	 */
	public function getRequest(): Request
	{
		return $this->request;
	}

	/**
	 * @param Request $request
	 * @return AbstractController
	 */
	public function setRequest(Request $request): AbstractController
	{
		$this->request = $request;
		return $this;
	}

	function getDependencyRegistrar(): DependencyRegistrar
	{
		return $this->dependencyRegistrar;
	}

	function setDependencyRegistrar(DependencyRegistrar $dependencyRegistrar): IDependencyRegistrarAware
	{
		$this->dependencyRegistrar = $dependencyRegistrar;
		return $this;
	}

	function getModelRepository(): ModelRepository
	{
		return $this->modelRepository;
	}

	function setModelRepository(ModelRepository $modelRepository): IModelRepositoryAware
	{
		$this->modelRepository = $modelRepository;
		return $this;
	}

	function setSessionManager(SessionManager $sessionManager): ISessionManagerAware
	{
		$this->sessionManager = $sessionManager;
		return $this;
	}

	function setAuthenticationService(AuthenticationService $authenticationService): IAuthenticationServiceAware
	{
		$this->authenticationService = $authenticationService;
		return $this;
	}

	protected function checkLogin($backendUser = false)
	{
		if (!$this->sessionManager->getUserId() || ($backendUser ? !$this->sessionManager->isBackendUser() : false)) {

			$this->request->redirect('/login');
		}
	}

}