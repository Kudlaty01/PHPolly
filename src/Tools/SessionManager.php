<?php

namespace Tools;
use Model\UserModel;

/**
 * Class SessionManager
 *
 * @package \Tools
 */
class SessionManager
{
	/**
	 * @return int|null
	 */
	public function getUserId()
	{
		return $_SESSION['user_id'];
	}

	/**
	 * @return bool|null
	 */
	public function isBackendUser()
	{
		return $_SESSION['backend_user'];
	}

	/**
	 * @param UserModel $result
	 * @return bool
	 */
	public function login(UserModel $result)
	{
		$_SESSION['user_id']      = $result->getUserId();
		$_SESSION['backend_user'] = $result->isBackendUser();
		return true;
	}

	public function logout()
	{
		unset($_SESSION['user_id']);
		unset($_SESSION['backend_user']);
	}
}