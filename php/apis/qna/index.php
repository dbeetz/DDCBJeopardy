<?php

require_once dirname(__DIR__, 2) . "/classes/autoloader.php";
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\DDCBJeopardy\Qna;

/**
 * API for the gameQna class
 *
 * @author Monica Alvarez <mmalvar13@gmail.com>
 **/

//Verify the session, start a session if not active
if(session_status() !== PHP_SESSION_ACTIVE){
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try{
	//Connect to mySQL
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/jeopardy.ini");

	//Determine with HTTP method was used: GET, PUT, or DELETE
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize the input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$qnaCategoryId = filter_input(INPUT_GET, "qnaCategoryId", FILTER_VALIDATE_INT);
	$qnaAnswer = filter_input(INPUT_GET, "qnaAnswer", FILTER_SANITIZE_STRING);
	$qnaPointVal = filter_input(INPUT_GET, "qnaPointVal", FILTER_VALIDATE_INT);
	$qnaQuestion = filter_input(INPUT_GET, "qnaQuestion", FILTER_SANITIZE_STRING);


	if($method === "GET"){
		//set XSRF cookie
		setXsrfCookie();

		//get a specific qna or all qnas and update reply
		if(empty($id) === false){
			$qna = Qna::getQnaByQnaId($pdo, $id);
			if($qna !== null){
				$reply->data = $qna;
			}
		}elseif(empty($qnaCategoryId) === false){
			$qna = Qna::getQnaByQnaCategoryId($pdo, $qnaCategoryId);
			if($qna !== null){
				$reply->data = $qna;
			}
		}elseif(empty($qnaAnswer) === false){
			$qna = Qna::getQnaByQnaAnswer($pdo, $qnaAnswer);
			if($qna !== null){
				$reply->data = $qna;
			}
		}elseif(empty($qnaPointVal) === false){
			$qna = Qna::getQnaByQnaPointVal($pdo, $qnaPointVal);
			if($qna !== null){
				$reply->data = $qna;
			}
		}elseif(empty($qnaQuestion) === false){
			$qna = Qna::getQnaByQnaQuestion($pdo, $qnaQuestion);
			if($qna !== null){
				$reply->data = $qna;
			}
		}else{
			$qnas = Qna::getAllQnas($pdo);
			if($qnas !== null){
				$reply->data = $qnas;
			}
		}


	}



}