<?php

namespace Tools\AwareInterface;

use Tools\DependencyRegistrar;

interface IDependencyRegistrarAware
{
	function setDependencyRegistrar(DependencyRegistrar $dependencyRegistrar): self;

	function getDependencyRegistrar(): DependencyRegistrar;

}