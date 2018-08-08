<?php

namespace Tools\AwareInterface;

use Tools\Db\IDbAdapter;

interface IDbAdapterAware
{
	function setDbAdapter(IDbAdapter $dbAdapter): self;

	function getDbAdapter(): IDbAdapter;

}