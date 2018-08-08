<?php

namespace Tools\Http\Factory;
use Tools\DependencyRegistrar;
use Tools\Factory\IServiceFactory;
use Tools\Http\Request;
use Tools\Routing;

/**
 * Class RequestFactory
 *
 * @package \Tools\Http\Factory
 */
class RequestFactory implements IServiceFactory
{

	/**
	 * @param DependencyRegistrar $dependencyRegistrar
	 * @return mixed
	 */
	function createService(DependencyRegistrar $dependencyRegistrar)
	{
		$routing = $dependencyRegistrar->get(Routing::class);
		return new Request($routing);
	}
}