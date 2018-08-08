<?php

namespace Controller\Factory;

use Controller\PollsController;
use Service\PollsService;
use Tools\DependencyRegistrar;
use Tools\IController;


/**
 * Class IndexControllerFactory
 *
 * @package \Controller\Factory
 */
class PollsControllerFactory extends AbstractControllerFactory implements IControllerFactory
{

	/**
	 * @param DependencyRegistrar $dependencyRegistrar
	 * @return mixed
	 */
	function createService(DependencyRegistrar $dependencyRegistrar): IController
	{
		/**
		 * @var PollsService $pollsService
		 */
		$pollsService = $dependencyRegistrar->get(PollsService::class);
		return new PollsController($pollsService);
	}
}