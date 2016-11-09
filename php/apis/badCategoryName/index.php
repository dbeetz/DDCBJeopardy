<?php

require_once dirname(__DIR__, 2) . "/classes/autoloader.php";
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\DDCBJeopardy\GameQna;

/**
 * API for the badCategoryName class
 *
 * @author LB <baca.loren@gmail.com>
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
	//grab mySQL connection

	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/DDCBJeopardy.ini");

	//determine which HTTP method was used, what does the ? mean??
	$method = array_key_exists("HTTP_X_HTTP-METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input(Explain this) Where is the input coming from??
	$badCategoryNameCategoryId = filter_input(INPUT_GET, "badCategoryNameCategoryId", FILTER_VALIDATE_INT);
	$badCategoryNameGameId = filter_input(INPUT_GET, "badCategoryNameGameId", FILTER_VALIDATE_INT);
	$badCategoryNameName = filter_input(INPUT_GET, "badCategoryNameName", FILTER_SANITIZE_STRING);


	if($method === "GET") {
		//set XRF cookie
		setXsrfCookie();


		//get a specific company or all companies and update reply
		if((empty($badCategoryNameCategoryId)) === false) {
			$badCategoryNameName = BadCategoryName::getBadCategoryNameByBadCategoryNameCategoryId($pdo, $categoryId);
			if($company !== null) {
				$reply->data = $company;
			}
		} elseif((empty($companyAccountCreatorId)) === false) {
			$company = Company::getCompanyByCompanyAccountCreatorId($pdo, $companyAccountCreatorId);
			if($company !== null) {
				$reply->data = $company;
			}
		} elseif((empty($companyName)) === false) {
			$company = Company::getCompanyByCompanyName($pdo, $companyName);
			if($company !== null) {
				$reply->data = $company;
			}
		} elseif((empty($companyMenuText)) === false) {
			$company = Company::getCompanyByCompanyMenuText($pdo, $companyMenuText);
			if($company !== null) {
				$reply->data = $company;
			}
		} elseif((empty($companyDescription)) === false) {
			$company = Company::getCompanyByCompanyDescription($pdo, $companyDescription);
			if($company !== null) {
				$reply->data = $company;
			}
		} else {
			$companies = Company::getAllCompanys($pdo);
			if($companies !== null) {
				$reply->data = $companies;
			}
		}


//		ensure the person making changes is admin or owner and owns that account
	}



}







