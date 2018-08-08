<?php

namespace Service\Factory;

use Enum\Config\ConfigSections;
use Enum\DependencyRegisters;
use Enum\ErrorMessages;
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
	 * @throws \Exception
	 */
	function createService(DependencyRegistrar $dependencyRegistrar)
	{
		/** @var ModelRepository $modelRepository */
		$config          = $dependencyRegistrar->get(DependencyRegisters::CONFIG)[ConfigSections::PRIVATE];
		if (empty($config)) {
			echo ErrorMessages::NO_PRIVATE_SETTINGS_FILE;
			throw new \Exception(ErrorMessages::NO_PRIVATE_SETTINGS_FILE);
		}
		$modelRepository = $dependencyRegistrar->get(ModelRepository::class);
		return new InstallationService($config, $modelRepository);
	}
}