<?php

namespace Service;

use Model\AnswerModel;
use Model\PollModel;
use Model\UserAnswerModel;
use Model\UserModel;
use Tools\Db\ModelRepository;

/**
 * Class InstallationService
 *
 * @package \Service
 */
class InstallationService
{
	/**
	 * @var ModelRepository
	 */
	private $modelRepository;
	/**
	 * @var array
	 */
	private $config;

	/**
	 * InstallationService constructor.
	 * @param                 $config
	 * @param ModelRepository $modelRepository
	 */
	public function __construct(array $config, ModelRepository $modelRepository)
	{
		$this->modelRepository = $modelRepository;
		$this->config          = $config;
	}

	/**
	 * Perform installation of all tables and input main user
	 */
	public function install():bool
	{
		try {
			$this->modelRepository->install(UserModel::getConfig());
			$this->modelRepository->install(PollModel::getConfig());
			$this->modelRepository->install(AnswerModel::getConfig());
			$this->modelRepository->install(UserAnswerModel::getConfig());
			foreach ($this->config['users'] as $userConfig) {
				$newUser = new UserModel();
				$newUser->setLogin($userConfig['login'])
					->setPasswordHash(password_hash($userConfig['password'], PASSWORD_BCRYPT))
					->setBackendUser($userConfig['backendUser']);
				$newUserId = $this->modelRepository->add($newUser);
				if (!$newUserId) {
					throw new \Exception("Default user cannot be inserted!");
				}
			}
			return true;
		} catch (\Exception $e) {
			echo $e->getMessage();
			return false;
		}

	}

	/**
	 * Check if it is already installed
	 * @return bool
	 */
	public function installed():bool
	{
		try {
			$this->modelRepository->list(new UserModel(), ['login' => 'admin', 'backendUser' => 1], ['1']);
			$this->modelRepository->list(new PollModel(), null, ['1']);
			$this->modelRepository->list(new AnswerModel(), null, ['1']);
			$this->modelRepository->list(new UserAnswerModel(), null, ['1']);
		} catch (\Exception $e) {
			return false;
		} catch (\Error $e) {
			return false;
		}

		return true;

	}
}