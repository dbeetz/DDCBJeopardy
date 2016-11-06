<?php

require_once dirname(__DIR__, 2) . "/classes/autoloader.php";
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\DDCBJeopardy\GameQna;

/**
 * API for the gameQna class
 *
 * @author Zac Laudick <zaclaudick@gmail.com>
 **/

// verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	// grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/devconnect.ini");

	// determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$gameQnaGameId = filter_input(INPUT_GET, "gameQnaGameId", FILTER_VALIDATE_INT);
	$gameQnaQnaId = filter_input(INPUT_GET, "gameQnaQnaId", FILTER_VALIDATE_INT);
	$gameQnaPass = filter_input(INPUT_GET, "gameQnaPass", FILTER_VALIDATE_BOOLEAN);

	// make sure the id is valid for methods that require it
	if(($method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}

	// handle GET request, if id is present that gameQna is returned
	if($method === "GET") {
		// set XSRF cookie
		setXsrfCookie();

		// get a specific gameQna and update reply
		if(empty($id) === false) {
			$gameQna = GameQna::getGameQnaByGameQnaId($pdo, $id);
			if($gameQna !== null) {
				$reply->data = $gameQna;
			}
			// get gameQna by gameQnaGameId and update reply
		} elseif(empty($gameQnaGameId) === false) {
			$gameQna = GameQna::getGameQnaByGameQnaGameId($pdo, $gameQnaGameId);
			if($gameQna !== null) {
				$reply->data = $gameQna;
			}
			// get gameQna by gameQnaQnaId and update reply
		} elseif(empty($gameQnaQnaId) === false) {
			$gameQna = GameQna::getGameQnaByGameQnaQnaId($pdo, $gameQnaQnaId);
			if($gameQna !== null) {
				$reply->data = $gameQna;
			}
		}
	} elseif($method === "PUT") {

		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		if(empty($requestObject) === true) {
			throw(new InvalidArgumentException("No content to update", 405));
		}

		// retrieve the gameQna to update
		$gameQna = GameQna::getGameQnaByGameQnaId($pdo, $id);
		if($gameQna === null) {
			throw(new \RuntimeException("gameQna doesn't exist", 404));
		}

		// put the new pass value into gameQna and update it
		$gameQna->setGameQnaPass($requestObject->gameQnaPass);
		$gameQna->update($pdo);
		$reply->message = "gameQna updated successfully.";
	} else {
		throw (new \InvalidArgumentException("Invalid HTTP method request"));
	}

	// update the reply with exception information
} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTraceAsString();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}

header("Content type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

// encode and return the reply to the front end caller
echo json_encode($reply);