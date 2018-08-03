<?php
use Enum\Config\ConfigSections;
use Enum\Config\Db;
use Enum\Config\DbEngineTypes;

return [
	ConfigSections::DB => [
		Db::ENGINE => DbEngineTypes::SQLITE,
		Db::CONNECTION_STRING => 'sqlite:db/database.sqlite',
	],
];
