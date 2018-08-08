<?php

namespace Service\Factory;

use Enum\Config\ConfigSections;
use Enum\DependencyRegisters;
use Service\InstallationService;
use Tools\Db\ModelRepository;
use Tools\DependencyRegistrar;
use Tools\Factory\IServiceFactory;

/**
 * Class InstallationServiceFactory
 *
 * @package \Service\Factory
 */
class InstallationServiceFactory implements IServiceFactory
{

	/**
	 * @param DependencyRegistrar $dependencyRegistrar
	 * @return mixed
	 */
	function createService(DependencyRegistrar $dependencyRegistrar)
	{
		/** @var ModelRepository $modelRepository */
		$config          = $dependencyRegistrar->get(DependencyRegisters::CONFIG)[ConfigSections::PRIVATE];
		$modelRepository = $dependencyRegistrar->get(ModelRepository::class);
		return new InstallationService($config, $modelRepository);
	}
}