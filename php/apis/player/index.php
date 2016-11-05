<?php
require_once dirname(__DIR__, 2) . "/classes/autoload.php";
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\DDCBJeopardy\Game;

/**
 * api player
 * @author Eliot Ostling <it.treugott@gmail.com>
 **/
//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/encrypted-config.php");
	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	//sanitize input
	$playerGameId = filter_input(INPUT_GET, "playerGameId", FILTER_VALIDATE_INT);
	$playerStudentId = filter_input(INPUT_GET, "playerStudentId", FILTER_SANITIZE_STRING);
  $playerStudentCohortId = filter_input(INPUT_GET,"playerStudentCohortId", FILTER_VALIDATE_INT);
	//make sure the id is valid for methods that require it
  //TODO: Make sure all foreign keys are added
	if(($method === "DELETE") && ((empty($playerStudentId) === true || $playerGameId <= 0) || (empty($studentCohortId) === true || $playerId <= 0))) {
		throw(new \InvalidArgumentException("id cannot be empty or negative", 405));
	}

  if($method === "GET") {
  		//set XSRF cookie
  		setXsrfCookie();
  		if(empty($_SESSION["player"]) === true) {
  			throw(new RuntimeException("?", 401));
  		}
  		if(empty($playerGameId) === false && empty($playerStudentId) === false && empty($setPlayerStudentCohortId)) {
  			$playerGameId = DDCBJeopardy\Player::getPlayerGameIdAndPlayerStudentIdAndPlayerStudentCohortId($pdo, $playerGameId, $playerStudentId,$playerStudentCohortId);

  			if($playerGameId !== null) {
  				$reply->data = $playerGameId;
  			} else if(empty($playerStudentId) === false) {
  				$playerStudentId = DDCBJeopardy\Player::getPlayerStudentId($pdo, $playerStudentId);
  				$reply->data = $playerStudentId;
  			} else if(empty($playerStudentCohortId) === false) {
  				$playerStudentCohortId = DDCBJeopardy\Player::getPlayerStudentCohortId($pdo, $playerStudentCohortId);
  				$reply->data = $playerStudentCohortId;
  			}
