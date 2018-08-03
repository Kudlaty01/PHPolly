<?php
use Enum\Config\Routes as RouteCfg;
use Tools\Routing;

/**
 * Class Application
 *
 */
class Application
{

	/**
	 * @var Routing
	 */
	private $routing;

	/**
	 * @var array
	 */
	private $config;
	private $queryFactory;

	/**
	 * @param array $config
	 * @return $this
	 */
	public function init($config = null)
	{
		session_start();
		$this->config       = $config;
		$this->routing      = new Routing($config);
		$this->queryFactory = new \Tools\Db\QueryFactory($config[\Enum\Config\ConfigSections::DB]);

		return $this;
	}

	public function run()
	{
		$this->handleRouting();
		return $this;
	}

	private function handleRouting()
	{
		$url             = $this->routing->getCurrentUri();
		$route           = $this->routing->handleUrl($url);
		$action          = $route[\Enum\Config\Routes::ACTION] . 'Action';
		$controllerClass = $route[RouteCfg::CONTROLLER];
		$controller      = new $controllerClass;
		if (preg_match('/Controller$/', $controller)) {
			throw new \ErrorException('Wrong controller naming convention');
		}
		/** @var \Tools\View $viewResult */
		$viewResult = $controller->$action();
//		echo 'Controller class: '.$controllerClass.PHP_EOL;
		if (!$viewResult->getTemplate()) {
			$viewResult->setTemplate(join(DIRECTORY_SEPARATOR, [
				strtolower(preg_replace('/Controller$/', '', array_reverse(explode('\\', $controllerClass))[0])),
				preg_replace('/Action$/', '', $action),
			]));
		}
		$viewResult->setNavigation($route[RouteCfg::NAVIGATION]);
		$viewResult->render();

	}

}