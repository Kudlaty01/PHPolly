<?php

use Controller\IndexController;
use Enum\Config\ConfigSections;
use Enum\Config\Routes;

return [
	ConfigSections::ROUTES => [
		'index' => [
			Routes::ROUTE      => '/index',
			Routes::CONTROLLER => IndexController::class,
			Routes::NAVIGATION => [
				'backend' => '/polls',
			],
		],
	],
];
