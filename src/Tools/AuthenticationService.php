<?php

namespace Tools;

use Model\UserModel;
use Tools\Db\ModelRepository;

/**
 * Class AuthenticationService
 *
 * @package \Tools
 */
class AuthenticationService
{
	/**
	 * @var ModelRepository
	 */
	private $modelRepository;
	/**
	 * @var SessionManager
	 */
	private $sessionManager;


	/**
	 * AuthenticationService constructor.
	 * @param ModelRepository $modelRepository
	 * @param SessionManager  $sessionManager
	 */
	public function __construct(ModelRepository $modelRepository, SessionManager $sessionManager)
	{
		$this->modelRepository = $modelRepository;
		$this->sessionManager  = $sessionManager;
	}

	/**
	 * @param string $login
	 * @param string $password
	 * @return bool
	 */
	public function login(string $login, string $password): bool
	{
		/** @var UserModel $result */
		$result = $this->modelRepository->findOneBy(new UserModel, [
			'login' => $login,
		]);
		if (password_verify($password, $result->getPasswordHash())) {
			$this->sessionManager->login($result);
			return true;
		}
		return false;

	}

}