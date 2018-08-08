<?php

namespace Model\Config\Db;

interface IDbModelConfig
{
	/**
	 * converts config class to an array for further parsing
	 * @return array
	 */
	function toArray();
}