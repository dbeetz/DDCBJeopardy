<?php

namespace Edu\Cnm\DDCBJeopardy;

require_once("autoloader.php");

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
	 * @param int $newGameDailyDoubleId id of the daily double of the Game
	 * @param \DateTime|string|null $newGameDateTime date and time of the Game, or null if set to the current date and time
	 * @param int $newGameFinalJeopardyId id of the final jeopardy of the Game
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct(int $newGameId = null, int $newGameDailyDoubleId, $newGameDateTime = null, int $newGameFinalJeopardyId) {
		try {
			$this->setGameId($newGameId);
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

	//Begin Accessors and Mutators

	/**
	 * accessor method for the game id
	 *
	 * @return int|null value of game id
	 **/
	public function getGameId() {
		return ($this->gameId);
	}

	/**
	 * mutator method for the game id
	 *
	 * @param int|null $newGameId new value of game id
	 * @throws \RangeException if $newGameId is not positive
	 * @throws \TypeError if $newGameId is not an integer
	 **/
	public function setGameId(int $newGameId = null) {
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
	 * accessor method for the game daily double id
	 *
	 * @return int value of game daily double id
	 **/
	public function getGameDailyDoubleId() {
		return ($this->gameDailyDoubleId);
	}

	/**
	 * mutator method for the game daily double id
	 *
	 * @param int $newGameDailyDoubleId new value of game daily double id
	 * @throws \RangeException if $newGameDailyDoubleId is not positive
	 * @throws \TypeError if $newGameDailyDoubleId is not an integer
	 **/
	public function setGameDailyDoubleId(int $newGameDailyDoubleId = null) {
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
		return ($this->gameDateTime);
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
		$this->gameDateTime = $newGameDateTime;
	}

	/**
	 * accessor method for the game final jeopardy id
	 *
	 * @return int value of game final jeopardy id
	 **/
	public function getGameFinalJeopardyId() {
		return ($this->gameFinalJeopardyId);
	}

	/**
	 * mutator method for the game final jeopardy id
	 *
	 * @param int $newGameFinalJeopardyId new value of game final jeopardy id
	 * @throws \RangeException if $newGameFinalJeopardyId is not positive
	 * @throws \TypeError if $newGameFinalJeopardyId is not an integer
	 **/
	public function setGameFinalJeopardyId(int $newGameFinalJeopardyId = null) {
		//verify the game final jeopardy id is positive
		if($newGameFinalJeopardyId <= 0) {
			throw(new \RangeException("game final jeopardy id is not positive"));
		}

		//convert and store the game final jeopardy id
		$this->gameFinalJeopardyId = $newGameFinalJeopardyId;
	}

	// Begin PDOs
	/**
	 * inserts the Game into the MySQL database
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related error occurs
	 * @throws \TypeError when $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		//enforce that the game id is null (don't insert a game that already exists)
		if($this->gameId !== null) {
			throw(new \PDOException("Game is not new"));
		}
		//create the query template
		$query = "INSERT INTO game(gameDailyDoubleId, gameDateTime, gameFinalJeopardyId) VALUES (:gameDailyDoubleId, :gameDateTime, :gameFinalJeopardyId)";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place holders in the template
		$formattedDate = $this->gameDateTime->format("Y-m-d H:i:s");
		$parameters = ["gameDailyDoubleId" => $this->gameDailyDoubleId, "gameDateTime" => $formattedDate, "gameFinalJeopardyId" => $this->gameFinalJeopardyId];
		$statement->execute($parameters);
		//update the null game id with what MySQL just gave us
		$this->gameId = intval($pdo->lastInsertId());
	}

	/**
	 * deletes the Game from the MySQL database
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related error occurs
	 * @throws \TypeError when $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		//enforce that the game id is not null (cannot delete a game that does not exist)
		if($this->gameId === null) {
			throw(new \PDOException("Cannot delete game that does not exist"));
		}
		//create the query template
		$query = "DELETE FROM game WHERE gameId = :gameId";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place holders in the template
		$parameters = ["gameId" => $this->gameId];
		$statement->execute($parameters);
	}

	/**
	 * updates this game in MySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related error occurs
	 * @throws \TypeError when $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) {
		//enforce that the game id is not null (cannot update a game that does not exist)
		if($this->gameId === null) {
			throw(new \PDOException("Cannot update game that does not exist"));
		}
		//create the query template
		$query = "UPDATE game SET gameDailyDoubleId = :gameDailyDoubleId, gameDateTime = :gameDateTime, gameFinalJeopardyId = :gameFinalJeopardyId WHERE gameId = :gameId";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place holders in the template
		$formattedDate = $this->gameDateTime->format("Y-m-d H:i:s");
		$parameters = ["gameDailyDoubleId" => $this->gameDailyDoubleId, "gameDateTime" => $formattedDate, "gameFinalJeopardyId" => $this->gameFinalJeopardyId, "gameId" => $this->getGameId()];
		$statement->execute($parameters);
	}

	/**
	 * get the game by game id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $gameId the game id to search for
	 * @return Game|null either the project, or null if not found
	 * @throws \PDOException when mySQL related errors are found
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getGameByGameId(\PDO $pdo, $gameId) {
		//sanitize the game id before searching
		if($gameId <= 0) {
			throw(new \PDOException ("Game id is not positive"));
		}
		//create query template
		$query = "SELECT gameId, gameDailyDoubleId, gameDateTime, gameFinalJeopardyId FROM game WHERE gameId = :gameId";
		$statement = $pdo->prepare($query);
		//bind the project id to the place holder in the template
		$parameters = array("gameId" => $gameId);
		$statement->execute($parameters);
		//grab the game from mySQL
		try {
			$game = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$game = new Game($row["gameId"], $row["gameDailyDoubleId"], $row["gameDateTime"],
					$row["gameFinalJeopardyId"]);
			}
		} catch(\Exception $exception){
			//if the row couldn't be converted rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($game);
	}

	//jsonSerialize
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["gameDateTime"] = ($this->gameDateTime->format("U")) * 1000;
		return ($fields);
	}
}
