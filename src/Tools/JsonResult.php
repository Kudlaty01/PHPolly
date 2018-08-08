<?php

namespace Tools;

/**
 * Class JsonResult
 *
 * @package \Tools
 */
class JsonResult extends AbstractActionResult implements IActionResult
{
	/**
	 * JsonResult constructor.
	 * @param $data
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function render(): string
	{
		return json_encode($this->data);
	}
}