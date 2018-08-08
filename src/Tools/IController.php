<?php

namespace Tools;

use Tools\Http\Request;

interface IController
{

	/**
	 * Controller specific additional scripts
	 * @return array
	 */
	function getScripts(): array;
	/**
	 * @return Request
	 */
	function getRequest() : Request;

	/**
	 * @param Request $request
	 * @return AbstractController
	 */
	function setRequest(Request $request) : AbstractController;

}