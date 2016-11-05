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
  			}else if($method === "POST") {
		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);
		//make sure is available
		if(empty($requestObject->playerId) === true) {
			throw(new \InvalidArgumentException ("NoPlayer Exists. So there.", 405));
		}
		if(empty($_SESSION["player"]) === true) {
			throw(new RuntimeException(".", 401));
		}
		$playerId = new DDCBJeopardy\Player($requestObject->playerId, $_SESSION["player"]->getplayerId());
		$playerId->insert($pdo);
		// update reply
		$reply->message = "created OK";
  }else if($method === "DELETE") {
		verifyXsrf();
		// retrieve the Favorite Player to be deleted
		$playerId = DDCBJeopardy\Player::getPlayerIdAndPlayerGameIdAndPlayerStudentCohortId($pdo, $playerId, $playerGameId, $playerStudentCohortId);
		if($playerId === null) {
			throw(new \RuntimeException("DNE: Does not exist", 404));
		}

		$playerId->delete($pdo);
		// update reply
		$reply->message = " Player deleted OK";
	} else {
		throw (new \InvalidArgumentException("Invalid HTTP method request"));
	}
} catch
(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTraceAsString();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
	//wat
}
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
// encode and return reply to front end caller
echo json_encode($reply);
