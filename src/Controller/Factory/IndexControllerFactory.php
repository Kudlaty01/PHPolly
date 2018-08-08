<?php

namespace Controller\Factory;

use Controller\IndexController;
use Service\InstallationService;
use Service\PollsService;
use Tools\DependencyRegistrar;
use Tools\IController;


/**
 * Class IndexControllerFactory
 *
 * @package \Controller\Factory
 */
class IndexControllerFactory extends AbstractControllerFactory implements IControllerFactory
{

	/**
	 * @param DependencyRegistrar $dependencyRegistrar
	 * @return mixed
	 */
	function createService(DependencyRegistrar $dependencyRegistrar): IController
	{
		/**
		 * @var InstallationService $installationService
		 */
		$installationService = $dependencyRegistrar->get(InstallationService::class);
		$pollsService        = $dependencyRegistrar->get(PollsService::class);
		return new IndexController($installationService, $pollsService);
	}
}