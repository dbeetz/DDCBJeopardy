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