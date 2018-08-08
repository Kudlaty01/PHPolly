<?php

namespace Controller\Factory;

use Tools\IController;
use Tools\DependencyRegistrar;

interface IControllerFactory
{
	/**
	 * @param DependencyRegistrar $dependencyRegistrar
	 * @return IController
	 */
	function createService(DependencyRegistrar $dependencyRegistrar): IController;

}