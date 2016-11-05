<?php

require_once("autoloader.php");

/**
 * This class manages players in one game
 *
 * @author Eliot Ostling
 */
class player implements JsonSerialize {

	private $playerId;

	private $playerGameId;

	private $playerStudentId;

	private $playerStudentCohortId;


	public function __construct(int $playerId, int $playerGameId, string $playerStudentId, int $PlayerStudentCohortId) {
		try {
			$this->setPlayerId($newPlayerId);
			$this->setPlayerGameId($newPlayerGameId);
			$this->setPlayerStudentId($newPlayerStudentId);
			$this->setPlayerStudentCohortId($newPlayerStudentCohortId);
		} catch(RangeException $range) {
			throw new RangeException($range->getMessage(), 0, $range);
		} catch(InvalidArgumentException $invalidArgument) {
			throw new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument);
		} catch(Exception $exception) {
			throw new Exception($exception->getMessage(), 0, $exception);
		}
	}

	/**
	 * Accessor for player ID
	 *
	 * @return int
	 */
	public function getPlayerId() {
		return $this->playerId;
	}

//Mutator

	public function setPlayerId(int $newPlayerId = null) {
		if($newPlayerId === null) {
			$this->playerId = null;
			return;
		}
		if($newPlayerId <= 0) {
			throw(new \RangeException("No"));
		}

		$this->playerId = $newPlayerId;
	}


	/**
	 * Accessor for Game ID
	 *
	 * @return int
	 */
	public function getPlayerGameId() {
		return $this->playerGameId;
	}

//Mutator

	public function setPlayerGameId(int $newPlayerGameId = null) {
		if($newPlayerGameId === null) {
			$this->playerGameId = null;
			return;
		}
		if($newPlayerGameId <= 0) {
			throw(new \RangeException("No"));
		}

		$this->playerGameId = $newPlayerGameId;
	}

//meh
	/**
	 * Accessor for Player student ID
	 *
	 * @return string
	 */
	public function getPlayerStudentId() {
		return $this->playerStudentId;
	}

//Mutator

	public function setPlayerStudentId(string $newPlayerStudentId) {
		// verify the link profile username is secure
		$newPlayerStudentId = trim($newPlayerStudentId);
		$newPlayerStudentId = filter_var($newPlayerStudentId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newPlayerStudentId) === true) {
			throw(new \InvalidArgumentException("Wrong"));
		}
		// verify the link event name will fit in the database
		if(strlen($newPlayerStudentId) > 9) {
			throw(new \RangeException("Incorrect again.."));
		}
		$this->playerStudentId = $newPlayerStudentId;
	}

	/**
	 * Accessor for  Player Student Cohort Id
	 *
	 * @return int
	 */
	public function getPlayerStudentCohortId() {
		return $this->playerStudentCohortId;
	}

//Mutator

	public function setPlayerStudentCohortId(int $newPlayerStudentCohortId = null) {
		if($newPlayerStudentCohortId === null) {
			$this->playerStudentCohortId = null;
			return;
		}
		if($newPlayerStudentCohortId <= 0) {
			throw(new \RangeException("No"));
		}

		$this->playerStudentCohortId = $newPlayerStudentCohortId;
	}

	/*PDO/SQL Methods*/

	/**
	 * inserts this Player into mySQL
	 *
	 * @param \PDO $pdo is the PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 *
	 **/

	public function insert(\PDO $pdo) {

		if($this->playerId === null || $this->playerId === null) {
			throw(new \InvalidArgumentException("The foreign key cannot be null!"));
		}
		if($this->playerGameId === null || $this->playerGameId === null) {
			throw(new \InvalidArgumentException("The foreign key cannot be null!"));
		}
		if($this->playerStudentId === null || $this->playerStudentId === null) {
			throw(new \InvalidArgumentException("The foreign key cannot be null!"));
		}
		if($this->playerStudentCohortId === null || $this->playerStudentCohortId === null) {
			throw(new \InvalidArgumentException("The Player Student Cohot ID cannot be null!"));
		}

		/*----Create query template-----*/
		$query = "INSERT INTO player(playerId, playerGameId, playerStudentId, 
playerStudentCohortId) VALUES(:playerId, :playerGameId, :playerStudentId, :playerStudentCohortId)";

		$statement = $pdo->prepare($query);

		$parameters = ["playerId" => $this->playerId, "playerGameId" => $this->playerGameId, "playerStudentId" => $this->playerStudentId, "playerStudentCohortId" => $this->playerStudentCohortId];

		$statement->execute($parameters);
	}
