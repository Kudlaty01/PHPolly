<?php

spl_autoload_register(function ($class) {
//	echo "Klasa: " . $class . '</br>';
	$pathElements = explode('\\', $class);

	$path = 'src' . DIRECTORY_SEPARATOR . join(DIRECTORY_SEPARATOR, $pathElements) . '.php';

	include $path;

});
