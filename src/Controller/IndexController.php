<?php

namespace Controller;

use Tools\ViewResult;

class IndexController
{
	/**
	 * IndexController constructor.
	 */
	public function __construct()
	{

	}

	public function indexAction()
	{
		$msg = "Welcome to the polling application!";
		return new ViewResult(['msg' => $msg]);
	}

}
