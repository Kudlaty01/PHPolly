<?php

namespace Tools;

/**
 * abstract class AbstractActionResult
 *
 * @package \Tools
 */
abstract class AbstractActionResult implements IActionResult
{
	protected $data;

	/**
	 * @return array
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * @param array $data
	 * @return AbstractActionResult
	 * t
	 */
	public function setData(array $data): AbstractActionResult
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	abstract public function render(): string;

}