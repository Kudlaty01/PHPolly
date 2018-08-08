<?php

namespace Service\Factory;

use Service\PollsService;
use Tools\Db\ModelRepository;
use Tools\DependencyRegistrar;
use Tools\Factory\IServiceFactory;

/**
 * Class PollsServiceFactory
 *
 * @package \Service\Factory
 */
class PollsServiceFactory implements IServiceFactory
{

	/**
	 * @param DependencyRegistrar $dependencyRegistrar
	 * @return mixed
	 */
	function createService(DependencyRegistrar $dependencyRegistrar)
	{
		$modelRepository = $dependencyRegistrar->get(ModelRepository::class);
		return new PollsService($modelRepository);
	}
}