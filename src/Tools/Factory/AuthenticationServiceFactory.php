<?php

namespace Tools\Factory;

use Tools\AuthenticationService;
use Tools\Db\ModelRepository;
use Tools\DependencyRegistrar;
use Tools\SessionManager;

/**
 * Class AuthenticationServiceFactory
 *
 * @package \Tools\Factory
 */
class AuthenticationServiceFactory implements IServiceFactory
{
	/**
	 * @param DependencyRegistrar $dependencyRegistrar
	 * @return mixed
	 */
	function createService(DependencyRegistrar $dependencyRegistrar)
	{
		/**
		 * @var ModelRepository $modelRepository
		 * @var SessionManager  $sessionManager
		 */
		$modelRepository = $dependencyRegistrar->get(ModelRepository::class);
		$sessionManager  = $dependencyRegistrar->get(SessionManager::class);
		return new AuthenticationService($modelRepository, $sessionManager);
	}
}