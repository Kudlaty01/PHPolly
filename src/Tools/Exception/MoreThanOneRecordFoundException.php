<?php

namespace Tools\Exception;

use Enum\ExceptionMessages;
use Exception;

/**
 * Class MoreThanOneRecordFoundException
 *
 * @package \Tools\Exception
 */
class MoreThanOneRecordFoundException extends \Exception
{
	/**
	 * @inheritDoc
	 */
	public function __construct($message = null, $code = 0, Exception $previous = null)
	{
		parent::__construct($message ?? ExceptionMessages::TOO_MANY_RECORDS_FOUND, $code, $previous);
	}


}