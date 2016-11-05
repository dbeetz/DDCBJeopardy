<?php
require_once dirname(dirname(__DIR__)) . "/classes/autoloader.php";
require_once dirname(dirname(__DIR__)) . "/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\DDCBJeopardy\Game;

/**
 * API for the Game class
 *
 * @author Devon Beets dbeetzz@gmail.com
 **/

//Verify XSRF
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// Prepare a empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	// Grab the MySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/jeopardy.ini");

	// Determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// Sanitize inputs
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$gameDailyDoubleId = filter_input(INPUT_GET, "gameDailyDoubleId", FILTER_VALIDATE_INT);
	$gameFinalJeopardyId = filter_input(INPUT_GET, "gameDailyDoubleId", FILTER_VALIDATE_INT);

	// Make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id cannot be negative or empty", 405));
	}
} 
