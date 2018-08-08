<?php

namespace Tools;

use Enum\Config\ConfigSections;
use Enum\Config\Routes;

/**
 * Class Routing
 *
 * @package \Tools
 */
class Routing
{
	/**
	 * @var array
	 */
	private $config;
	private $routes;

	/**
	 * Routing constructor.
	 * @param array $config
	 */
	public function __construct($config)
	{
		$this->config = $config;
		$this->routes = $config[ConfigSections::ROUTES];
	}

	/**
	 * @param string|null $url url to be handled. Gets current if provided null
	 * @return array|bool
	 */
	public function handleUrl($url = null)
	{
		if ($url === null) {
			$url = $this->getCurrentUri();
		}
		$routes = array_values(array_filter(explode('/', $url)));

		$mainRoutePath = empty($routes) ? 'index' : $routes[0];
		$routeConfig   = array_combine(array_keys($this->routes), array_column($this->routes, Routes::ROUTE));
		$knownRouteKey = array_search('/' . $mainRoutePath, $routeConfig);
		if ($knownRouteKey == false) {
			$knownRouteKey = 'index';
			array_unshift($routes, 'index');
		}
		$knownRoute     = $this->routes[$knownRouteKey];
		$matchingResult = $this->matchUrlToRoute($routes, $knownRoute);
		return $matchingResult;
	}

	function getCurrentUri()
	{
		$basePath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
		$uri      = substr($_SERVER['REQUEST_URI'], strlen($basePath));
		if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
		$uri = '/' . trim($uri, '/');
		return $uri;
	}

	/**
	 * @param array $routes
	 * @param array $knownRoute
	 * @return array|bool route on match and else FALSE
	 */
	private function matchUrlToRoute($routes, $knownRoute)
	{
		$parametersOffset = 2;
		if (count($routes) > 1 && in_array($routes[1] . 'Action', get_class_methods($knownRoute[Routes::CONTROLLER]))) {
			$knownRoute[Routes::ACTION] = $routes[1];
		} else {
			$knownRoute[Routes::ACTION] = 'index';
			$parametersOffset--;
		}
		$parameters = array_slice($routes, $parametersOffset);
		if (array_key_exists($knownRoute[Routes::ACTION], $knownRoute[Routes::ACTION_PARAMETERS])) {
			$paramKeys = $knownRoute[Routes::ACTION_PARAMETERS][$knownRoute[Routes::ACTION]];
			$parameters = array_combine($paramKeys, array_slice($parameters, 0, count($paramKeys)));
		}
		$knownRoute[Routes::PARAMETERS] = $parameters;
		return $knownRoute;
	}

}