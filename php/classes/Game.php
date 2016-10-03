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

	/**
	*
	* constructor for this Game
	*
	* @param int|null $newGameId id of this Game or null if a new Game
	* @param int $newGameStudentId id of the students playing the Game
	* @param int $newGameDailyDoubleId id of the daily double of the Game
	* @param \DateTime|string|null $newGameDateTime date and time of the Game, or null if set to the current date and time
	* @param int $newGameFinalJeopardyId id of the final jeopardy of the Game
	* @throws \InvalidArgumentException if data types are not valid
	* @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	* @throws \TypeError if data types violate type hints
	* @throws \Exception if some other exception occurs
	**/
	public function __construct(int $newGameId = null, int $newGameStudentId, int $newGameDailyDoubleId, $newGameDateTime = null, int $newGameFinalJeopardyId) {
		try {
			$this->setGameId($newGameId);
			$this->setGameStudentId($newGameStudentId);
			$this->setGameDailyDoubleId($newGameDailyDoubleId);
			$this->setGameDateTime($newGameDateTime);
			$this->setGameFinalJeopardyId($newGameFinalJeopardyId);
		} catch(\InvalidArgumentException $invalidArgument) {
			//rethrow the exception to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			//rethrow the exception to the caller
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			//rethrow the exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception) {
			//rethrow the exception to the caller
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	* accessor method for the game id
	*
	* @return int|null value of game id
	**/
	public function getGameId() {
		return($this->gameId);
	}

	/**
	* mutator method for the game id
	*
	* @param int|null $newGameId new value of game id
	* @throws \RangeException if $newGameId is not positive
	* @throws \TypeError if $newGameId is not an integer
	**/
	public function setGameId() {
		//base case: if the game id is null, then this is a new game without a MySQL assigned id yet
		if($newGameId === null) {
			$this->gameId = null;
			return;
		}

		//verify the game id is positive
		if($newGameId <= 0) {
			throw(new \RangeException("game id is not positive"));
		}

		//convert and store the game id
		$this->gameId = $newGameId;
	}

	/**
	* accessor method for the game student id
	*
	* @return int value of game student id
	**/
	public function getGameStudentId() {
		return($this->getGameStudentId);
	}

	/**
	* mutator method for the game student id
	*
	* @param int $newGameStudentId new value of game student id
	* @throws \RangeException if $newGameStudentId is not positive
	* @throws \TypeError if $newGameStudentId is not an integer
	**/
	public function setGameStudentId() {
		//verify the game student id is positive
		if($newGameStudentId <= 0) {
			throw(new \RangeException("game student id is not positive"));
		}

		//convert and store the game student id
		$this->gameStudentId = $newGameStudentId;
	}

	/**
	* accessor method for the game daily double id
	*
	* @return int value of game daily double id
	**/
	public function getGameDailyDoubleId() {
		return($this->getGameDailyDoubleId);
	}

	/**
	* mutator method for the game daily double id
	*
	* @param int $newGameDailyDoubleId new value of game daily double id
	* @throws \RangeException if $newGameDailyDoubleId is not positive
	* @throws \TypeError if $newGameDailyDoubleId is not an integer
	**/
	public function setGameDailyDoubleId() {
		//verify the game daily double id is positive
		if($newGameDailyDoubleId <= 0) {
			throw(new \RangeException("game daily double id is not positive"));
		}

		//convert and store the game daily double id
		$this->gameDailyDoubleId = $newGameDailyDoubleId;
	}

	/**
	 * accessor method for the Game date and time
	 *
	 * @return \DateTime value of the Game date and time
	 **/
	public function getGameDateTime() {
		return($this->gameDateTime);
	}
	/**
	 * mutator method for the Game date and time
	 *
	 * @param \DateTime|string|null $newGameDateTime game date and time as a DateTime object, or null to load the current time
	 * @throws \InvalidArgumentException if $newGameDateTime is not a valid object or string
	 * @throws \RangeException if $newGameDateTime is a date that does not exist
	 **/
	public function setGameDateTime($newGameDateTime = null) {
		//base case: if the date and time are null, use the current date and time
		if($newGameDateTime === null) {
			$this->gameDateTime = new \DateTime();
			return;
		}
		//store the game date and time
		try {
			$newGameDateTime = self::validateDateTime($newGameDateTime);
		} catch(\InvalidArgumentException $invalidArgument) {
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			throw(new \RangeException($range->getMessage(), 0, $range));
		}
		$this->gameDateTime =$newGameDateTime;
	}

	/**
	* accessor method for the game final jeopardy id
	*
	* @return int value of game final jeopardy id
	**/
	public function getGameFinalJeopardyId() {
		return($this->getGameFinalJeopardyId);
	}

	/**
	* mutator method for the game final jeopardy id
	*
	* @param int $newGameFinalJeopardyId new value of game final jeopardy id
	* @throws \RangeException if $newGameFinalJeopardyId is not positive
	* @throws \TypeError if $newGameFinalJeopardyId is not an integer
	**/
	public function setGameFinalJeopardyId() {
		//verify the game final jeopardy id is positive
		if($newGameFinalJeopardyId <= 0) {
			throw(new \RangeException("game final jeopardy id is not positive"));
		}

		//convert and store the game final jeopardy id
		$this->gameFinalJeopardyId = $newGameFinalJeopardyId;
	}

}
