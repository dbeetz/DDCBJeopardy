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

  public function update(\PDO $pdo) {
		if ($this->playerId === null) {
			throw new \PDOException("Update is wrong.");
		}
		// Create query template
		$query = "UPDATE playerId SET playerGameId = :playerGameId, playerStudentId = :playerStudentId, playerStudentCohortId = :playerStudentCohortId WHERE playerId = :playerId";
		$statement = $pdo->prepare($query);
		$parameters = ["playerId" => $this->playerId, "playerGameId" => $this->playerGameId, "playerStudentId" => $this->playerStudentId, "playerStudentCohortId" => $this->playerStudentCohortId];
		$statement->execute($parameters);
	}

	public function delete(\PDO $pdo) {

		if($this->playerId === null || $this->playerId === null) {
			throw(new \InvalidArgumentException("Cannot delete a player that doesnt exist!"));
		}
		if($this->playerGameId === null || $this->playerGameId === null) {
			throw(new \InvalidArgumentException("Cannot delete a player from an unexistent game!"));
		}
		if($this->playerStudentId === null || $this->playerStudentId === null) {
			throw(new \InvalidArgumentException("The student Id is null!"));
		}

		/*----Create query template-----*/
		$query = "DELETE FROM player WHERE playerId = :playerId AND playerGameId = :playerGameId AND playerStudentId = :playerStudentId";

		$statement = $pdo->prepare($query);

		$parameters = ["playerId" => $this->playerId, "playerGameId" => $this->playerGameId, "playerStudentId => $this->playerStudentId"];

		$statement->execute($parameters);

	}

  public static function getPlayerbyPlayerId(\PDO $pdo, $playerId)
   {
       if($playerId <= 0) {
           throw(new \PDOException("wrong"));
       }
       $query = "SELECT playerId, playerGameId, playerStudentId, playerStudentCohortId  FROM player WHERE playerId = :playerId";
       $statement = $pdo->prepare($query);
       $parameters = array("playerId" => $playerId);
       $statement->execute($parameters);
       try {
           $player = null;
           $statement->setFetchMode(\PDO::FETCH_ASSOC);
           $row = $statement->fetch();
           if($row !== false) {
               $player = new player($row["playerId"], $row["playerGameId"], $row["playerStudentId"],$row["playerStudentCohortId"]);
           }
       } catch(\Exception $exception) {
           throw(new \PDOException($exception->getMessage(), 0, $exception));
       }
       return ($player);
   }

	public static function getPlayerByPlayerGameId(\PDO $pdo, $playerGameId)
	{
		if($playerGameId <= 0) {
			throw(new \PDOException("wrong"));
		}
		$query = "SELECT playerId, playerGameId, playerStudentId, playerStudentCohortId  FROM player WHERE playerGameId = :playerGameId";
		$statement = $pdo->prepare($query);
		$parameters = array("playerGameId" => $playerGameId);
		$statement->execute($parameters);
		try {
			$player = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$player = new player($row["playerId"], $row["playerGameId"], $row["playerStudentId"],$row["playerStudentCohortId"]);
           }
		} catch(\Exception $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($player);
	}
//meh
  public static function getPlayerByPlayerStudentId(\PDO $pdo, $playerStudentId)
	{
		if($playerStudentId <= 0) {
			throw(new \PDOException("wrong"));
		}
		$query = "SELECT playerId, playerGameId, playerStudentId, playerStudentCohortId  FROM player WHERE playerStudentId = :playerStudentId";
		$statement = $pdo->prepare($query);
		$parameters = array("playerStudentId" => $playerStudentId);
		$statement->execute($parameters);
		try {
			$player = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$player = new player($row["playerId"], $row["playerGameId"], $row["playerStudentId"],$row["playerStudentCohortId"]);
           }
		} catch(\Exception $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($player);
	}

}
