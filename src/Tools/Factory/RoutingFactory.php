<?php

namespace Tools\Factory;
use Enum\DependencyRegisters;
use Tools\DependencyRegistrar;
use Tools\Routing;

/**
 * Class RoutingFactory
 *
 * @package \Tools\Db\Factory
 */
class RoutingFactory implements IServiceFactory
{

	/**
	 * @param DependencyRegistrar $dependencyRegistrar
	 * @return mixed
	 */
	function createService(DependencyRegistrar $dependencyRegistrar)
	{
		$config = $dependencyRegistrar->get(DependencyRegisters::CONFIG);
		return new Routing($config);
	}
}