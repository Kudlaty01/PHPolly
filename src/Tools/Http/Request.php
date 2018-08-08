<?php

namespace Tools\Http;

use Enum\Config\Routes;
use Tools\Routing;

/**
 * Class Request
 *
 * @package \Tools
 */
class Request
{
	/**
	 * @var Routing
	 */
	private $routing;
	/**
	 * @var array
	 */
	private $params;

	/**
	 * Request constructor.
	 * @param Routing $routing
	 */
	public function __construct(Routing $routing)
	{
		$this->routing = $routing;
		$currentRoute  = $routing->handleUrl();
		$this->params  = $currentRoute[Routes::PARAMETERS];
	}


	/**
	 * Is it post or what?
	 * @return bool
	 */
	public function isPost(): bool
	{
		return $_SERVER["REQUEST_METHOD"] == "POST";
	}

	/**
	 * @param string|null $key
	 * @return mixed
	 */
	public function getPost(string $key = null)
	{
		return $key ? $_POST[$key] : $_POST;
	}

	/**
	 * Simple redirection method. Should be in Response class, but no time to implement that
	 * @param string $url
	 */
	public function redirect(string $url)
	{
		header("Location: $url");
	}

	/**
	 * @return array
	 */
	public function getParams(): array
	{
		return $this->params;
	}

	/**
	 * @param string $param
	 * @return string
	 */
	public function getParam(string $param): string
	{
		return $this->params[$param];
	}


}