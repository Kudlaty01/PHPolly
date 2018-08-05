<?php

namespace Enum;

/**
 * Enum class QueryType for determination of Query type
 *
 * @package \Enum
 */
class QueryType
{
	const SELECT = 'select';
	const INSERT = 'insert';
	const UPDATE = 'update';
	const DELETE = 'delete';
}