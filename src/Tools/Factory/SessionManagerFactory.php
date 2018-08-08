<?php

namespace Tools\Factory;
use Tools\DependencyRegistrar;
use Tools\SessionManager;

/**
 * Class SessionManagerFactory
 *
 * @package \Tools\Factory
 */
class SessionManagerFactory implements IServiceFactory
{

	/**
	 * @param DependencyRegistrar $dependencyRegistrar
	 * @return mixed
	 */
	function createService(DependencyRegistrar $dependencyRegistrar)
	{
		return new SessionManager();
	}
}