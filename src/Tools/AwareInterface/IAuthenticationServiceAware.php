<?php

namespace Tools\AwareInterface;

use Tools\AuthenticationService;

interface IAuthenticationServiceAware
{
	function setAuthenticationService(AuthenticationService $authenticationService): self;
}