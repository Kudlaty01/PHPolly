<?php
use Controller\Factory\IndexControllerFactory;
use Controller\Factory\PollsControllerFactory;
use Enum\Config\Routes as RouteCfg;
use Enum\DependencyRegisters;
use Enum\DependencyType;
use Service\InstallationService;
use Tools\DependencyRegistrar;
use Tools\Routing;
use Tools\SessionManager;

/**
 * Class Application
 *
 */
class Application
{

	/**
	 * @var array
	 */
	private $config;
	/**
	 * @var DependencyRegistrar
	 */
	private $dependencyRegistrar;
	/**
	 * @var SessionManager
	 */
	private $sessionManager;
	/**
	 * @var InstallationService
	 */
	private $installationService;

	/**
	 * @param array $config
	 * @return $this
	 */
	public function init($config = null)
	{
		session_start();
		$this->config              = $config;
		$this->dependencyRegistrar = new DependencyRegistrar($this->servicesConfig());
		$this->sessionManager      = $this->dependencyRegistrar->get(SessionManager::class);

		return $this;
	}

	/**
	 * This is where it all begins...
	 * @return $this
	 */
	public function run()
	{
		$this->handleRouting();
		return $this;
	}

	/**
	 * It has to go somewhere
	 * should have put that logic in Routing and in run() method
	 *
	 * @throws ErrorException
	 */
	private function handleRouting()
	{
		/** @var \Tools\Http\Request $request */
		$request = $this->dependencyRegistrar->get(\Tools\Http\Request::class);
		$route   = $request->getCurrentRoute();
		$action  = $route[\Enum\Config\Routes::ACTION];
		$controllerAction = $action . 'Action';
		$controllerClass  = $route[RouteCfg::CONTROLLER];
		$controller       = $this->dependencyRegistrar->get($controllerClass);

		if (preg_match('/Controller$/', $controller)) {
			throw new \ErrorException('Wrong controller naming convention');
		}
		/** @var \Tools\IActionResult $actionResult */
		$actionResult = $controller->$controllerAction();
//		echo 'Controller class: '.$controllerClass.PHP_EOL;
		$resultTypes = [
			'view' => $actionResult instanceof \Tools\ViewResult,
			'json' => $actionResult instanceof \Tools\JsonResult,
		];
		$resultType  = array_search(true, $resultTypes);
		switch ($resultType) {
			case 'view':
				/** @var \Tools\ViewResult $actionResult */
				if (!$actionResult->getTemplate()) {
					$actionResult->setTemplate(join(DIRECTORY_SEPARATOR, [
						strtolower(preg_replace('/Controller$/', '', array_reverse(explode('\\', $controllerClass))[0])),
						preg_replace('/Action$/', '', $controllerAction),
					]));
				}
				$actionResult->setNavigation(array_merge($route[RouteCfg::NAVIGATION], $this->sessionManager->getUserId()
					? ['logout' => '/logout']
					: ['login' => '/login']));
				$actionResult->setControllerScripts($controller->getScripts());
				break;
			case 'json':
				//TODO: should I do anything for JsonResult at all?
				break;
		}
		echo $actionResult->render();

	}

	/**
	 * defines all dependencies and where to get all services and controllers
	 * Should have been in a separate config
	 * @return array
	 */
	public function servicesConfig()
	{
		return [
			DependencyType::INVOKABLES => [
			],
			DependencyType::FACTORIES  => [
				DependencyRegisters::CONFIG         => /**
				 * @param DependencyRegistrar $dependencyRegistrar
				 * @return array
				 */
					function (DependencyRegistrar $dependencyRegistrar) {
						return $this->config;
					},
				/**
				 */
				\Tools\Db\IDbAdapter::class         =>
				/**
				 * set as a closure rather factory class to choose dynamically on different environments if ever planned
				 * @param DependencyRegistrar $dependencyRegistrar
				 * @return \Tools\Db\IDbAdapter
				 */
					function (DependencyRegistrar $dependencyRegistrar) {
						$config = $dependencyRegistrar->get(DependencyRegisters::CONFIG);
						return new \Tools\Db\SqliteDbAdapter($config[\Enum\Config\ConfigSections::DB]);
					},
				\Tools\Http\Request::class => \Tools\Http\Factory\RequestFactory::class,
				Routing::class                      => \Tools\Factory\RoutingFactory::class,
				\Tools\Db\ModelRepository::class    => \Tools\Db\Factory\ModelRepositoryFactory::class,
				\Controller\IndexController::class  => IndexControllerFactory::class,
				\Controller\PollsController::class  => PollsControllerFactory::class,
				SessionManager::class               => \Tools\Factory\SessionManagerFactory::class,
				\Tools\AuthenticationService::class => \Tools\Factory\AuthenticationServiceFactory::class,
				InstallationService::class          => \Service\Factory\InstallationServiceFactory::class,
				\Service\PollsService::class        => \Service\Factory\PollsServiceFactory::class,
			],
		];
	}

}