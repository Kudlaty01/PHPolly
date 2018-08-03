<?php
require 'init_autoloader.php';

$app = new Application();

$app->init(require 'config/config.php')->run();
