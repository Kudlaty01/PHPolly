<?php

namespace Tools;

interface IDependencyRegistrar
{
	function get(string $dependency);

}