<?php

namespace Edu\Cnm\Jeopardy;

require_once("autoload.php");

/**
* Game class for the Jeopardy project
*
* This class has everything required to build a session of a game of Jeopardy
*
* @author Devon Beets <dbeets@devonbeetsdesign.com>
*
* @version 1.0.0
**/

class Game implements \JsonSerializable {
	use ValidateDate;
	/**
	*
	* id for this Game, this is the primary key
	* @var int $gameId
	**/
	private $gameId;

	/**
	*
	* id for a student playing the Game, this is a foreign key
	* @var int $gameStudentId
	**/
	private $gameStudentId;

	/**
	*
	* id for the daily double of the Game
	* @var int $gameDailyDoubleId
	**/
	private $gameDailyDoubleId;

	/**
	*
	* date and time for this Game, in a PHP DateTime object
	* @var \DateTime $gameDateTime
	**/
	private $gameDateTime;

	/**
	*
	* id for the final jeopardy of the game Game
	* @var int $gameFinalJeopardyId
	**/
	private $gameFinalJeopardyId;
}
