<?php

$appConfig = [];
foreach (glob('config/autoload/*.php') as $config) {
	$appConfig = array_merge($appConfig, require $config);
}
return $appConfig;