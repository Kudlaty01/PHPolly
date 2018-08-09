<?php

namespace Enum;

/**
 * Class ErrorMessages
 *
 * @package \Enum
 */
class ErrorMessages
{
	const NO_MORE_POLLS = 'There is no valid poll at the moment to display';
	const WRONG_REQUEST_TYPE = 'Request must by of type POST';
	const POLL_EXPIRED = 'Poll has expired. Please try another one';
	const USER_ALREADY_VOTED = 'User has already voted in this poll!!';
	const DATABASE_ERROR = "Error placing vote in the poll!";
	const NO_PRIVATE_SETTINGS_FILE = 'Please rename config/autoload/private.php.dist to config/autoload/private.php and set the default passwords!';
	const NO_JS_COMPONENTS = "Please run 'yarn run setup' from the console in the main project directory";
	const WRONG_PASSWORD = "Login and password do not match!";
	const WRONG_LOGIN = "No account with provided login found!";
}