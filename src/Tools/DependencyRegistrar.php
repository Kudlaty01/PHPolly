<?php

namespace Tools;

use Controller\Factory\IControllerFactory;
use Enum\DependencyType;
use Tools\AwareInterface\IAuthenticationServiceAware;
use Tools\AwareInterface\IDbAdapterAware;
use Tools\AwareInterface\IDependencyRegistrarAware;
use Tools\AwareInterface\IModelRepositoryAware;
use Tools\AwareInterface\ISessionManagerAware;
use Tools\Db\IDbAdapter;
use Tools\Db\ModelRepository;
use Tools\Factory\IServiceFactory;
use Tools\Http\Request;

/**
 * Class DependencyRegistrar
 *
 * @package \Tools
 */
class DependencyRegistrar implements IDependencyRegistrar
{
	/**
	 * @var array
	 */
	private $config;


	/**
	 * DependencyRegistrar constructor.
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		//TODO: here factories!!!
		$this->config = $config;
	}

	/**
	 * @param string $dependency
	 * @return IController|mixed|null
	 */
	function get(string $dependency)
	{
		$result = null;
		if (array_key_exists($dependency, $this->config[DependencyType::INVOKABLES])) {
			$class  = $this->config[DependencyType::INVOKABLES][$dependency];
			$result = new $class;
		} else if (array_key_exists($dependency, $this->config[DependencyType::FACTORIES])) {
			$factoryClass = $this->config[DependencyType::FACTORIES][$dependency];
			if (is_callable($factoryClass)) {
				$result = $factoryClass($this);
			} else {
				$factory = new $factoryClass();
				if ($factory instanceof IControllerFactory || $factory instanceof IServiceFactory) {
					$result = $factory->createService($this);
				}
			}
		}

		if ($result instanceof IDependencyRegistrarAware) {
			$result->setDependencyRegistrar($this);
		}
		if ($result instanceof ISessionManagerAware) {
			$result->setSessionManager($this->get(SessionManager::class));
		}
		if ($result instanceof IAuthenticationServiceAware) {
			$result->setAuthenticationService($this->get(AuthenticationService::class));
		}
		if ($result instanceof IModelRepositoryAware) {
			$result->setModelRepository($this->get(ModelRepository::class));
		}
		if ($result instanceof IDbAdapterAware) {
			$result->setDbAdapter($this->get(IDbAdapter::class));
		}
		if ($result instanceof IController) {
			// Some dependencies required by controller, maybe to add sometime later
		}
		return $result;
	}
}