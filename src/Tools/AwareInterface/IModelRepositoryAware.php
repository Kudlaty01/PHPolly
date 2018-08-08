<?php

namespace Tools\AwareInterface;

use Tools\Db\ModelRepository;

interface IModelRepositoryAware
{
	function setModelRepository(ModelRepository $modelRepository): self;
}