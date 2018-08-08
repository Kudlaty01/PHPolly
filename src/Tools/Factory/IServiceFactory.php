<?php

namespace Tools\Factory;

use Tools\DependencyRegistrar;

/**
 * interface IServiceFactory
 *
 * @package \Controller\Factory
 */
interface IServiceFactory
{
	/**
	 * @param DependencyRegistrar $dependencyRegistrar
	 * @return mixed
	 */
	function createService(DependencyRegistrar $dependencyRegistrar);
}