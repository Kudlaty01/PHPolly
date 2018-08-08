<?php

namespace Tools\AwareInterface;
use Tools\SessionManager;

/**
 * Class ISessionManagerAware
 *
 * @package \Tools\AwareInterface
 */
interface ISessionManagerAware
{
	function setSessionManager(SessionManager $sessionManager): self;
}