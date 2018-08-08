<?php

namespace Tools;

interface IActionResult
{

	/**
	 * @return string
	 */
	function render(): string;

	/**
	 * @return array
	 */
	public function getData() : array;

	/**
	 * @param array $data
	 * @return IActionResult
	 */
	public function setData(array $data);
}