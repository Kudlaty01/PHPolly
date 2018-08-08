<?php

use Controller\IndexController;
use Controller\PollsController;
use Enum\Config\ConfigSections;
use Enum\Config\Routes;

return [
	ConfigSections::ROUTES => [
		'index' => [
			Routes::ROUTE      => '/index',
			Routes::CONTROLLER => IndexController::class,
			Routes::NAVIGATION => [
			],
		],
		'polls' => [
			Routes::ROUTE             => '/polls',
			Routes::CONTROLLER        => PollsController::class,
			Routes::ACTION_PARAMETERS => [
				'edit' => ['id'],
			],
			Routes::NAVIGATION        => [
				'list' => '/polls',
				'add'  => '/polls/add',
			],
		],
	],
];
