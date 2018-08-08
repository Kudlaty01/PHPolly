<?php

namespace Controller\Factory;

use Tools\AwareInterface\IDependencyRegistrarAware;
use Tools\DependencyRegistrar;

/**
 * Class AbstractControllerFactory
 *
 * @package \Controller\Tools
 */
abstract class AbstractControllerFactory implements IDependencyRegistrarAware
{
	private $dependencyRegistrar;

	function getDependencyRegistrar(): DependencyRegistrar
	{
		return $this->dependencyRegistrar;
	}

	function setDependencyRegistrar(DependencyRegistrar $dependencyRegistrar): IDependencyRegistrarAware
	{
		$this->dependencyRegistrar = $dependencyRegistrar;
		return $this;
	}

}