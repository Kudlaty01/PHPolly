<?php

namespace Tools\Db\Factory;

use Tools\Db\IDbAdapter;
use Tools\Db\ModelRepository;
use Tools\DependencyRegistrar;
use Tools\Factory\IServiceFactory;

/**
 * Class ModelRepositoryFactory
 *
 * @package \Tools\Db\Factory
 */
class ModelRepositoryFactory implements IServiceFactory
{

	/**
	 * @param DependencyRegistrar $dependencyRegistrar
	 * @return mixed
	 */
	function createService(DependencyRegistrar $dependencyRegistrar)
	{
		/** @var IDbAdapter $dbEngine */
		$dbEngine = $dependencyRegistrar->get(IDbAdapter::class);
		return new ModelRepository($dbEngine);
	}
}