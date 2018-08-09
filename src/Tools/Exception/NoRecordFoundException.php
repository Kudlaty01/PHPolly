<?php

namespace Tools\Exception;

use Enum\ExceptionMessages;
use Exception;

/**
 * Class NoRecordFoundException
 *
 * @package \Tools\Exception
 */
class NoRecordFoundException extends \Exception
{
	/**
	 * @inheritDoc
	 */
	public function __construct($message = null, $code = 0, Exception $previous = null)
	{
		parent::__construct($message ?? ExceptionMessages::NO_RECORDS_FOUND, $code, $previous);
	}

}