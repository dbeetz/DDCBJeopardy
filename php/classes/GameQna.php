<?php
namespace Edu\Cnm\DDCBJeopardy;

require_once("autoloader.php");

/**
 * GameQna class for Jeopardy
 *
 * @author Zac Laudick <zaclaudick@gmail.com>
 **/
class GameQna implements \JsonSerializable {
	/**
	 * id for this GameQna; this is the primary key
	 * @var int $gameQnaId
	 **/
	private $gameQnaId;

	/**
	 * id for the game using the gameQna; this is a foreign key
	 * @var int $gameQnaGameId
	 **/
	private $gameQnaGameId;

	/**
	 * id for the qna used in the gameQna; this is a foreign key
	 * @var int $gameQnaQnaId
	 **/
	private $gameQnaQnaId;

	/**
	 * constructor for this GameQna
	 *
	 * @param int|null $newGameQnaId id of this GameQna or null if new GameQna
	 * @param int $newGameQnaGameId foreign key from Game
	 * @param int $newGameQnaQnaId foreign key from Qna
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct(int $newGameQnaId = null, int $newGameQnaGameId, int $newGameQnaQnaId) {
		try {
			$this->setGameQnaId($newGameQnaId);
			$this->setGameQnaGameId($newGameQnaGameId);
			$this->setGameQnaQnaId($newGameQnaQnaId);
		} catch(\InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			// rethrows the exception to the caller
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			// rethrow the exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception) {
			// rethrow the exception to the caller
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for gameQnaId
	 * @return int|null value of gameQnaId
	 **/
	public function getGameQnaId() {
		return($this->gameQnaId);
	}

	/**
	 * mutator method for gameQnaId
	 *
	 * @param int|null $newGameQnaId new value of gameQnaId
	 * @throws \RangeException if $newGameQnaId is not positive
	 * @throws \TypeError if $newGameQnaId is not an integer
	 **/
	public function setGameQnaId(int $newGameQnaId = null) {
		// base case: if the gameQnaId is null, this is a new gameQna id without a mySQL assigned id (yet)
		if($newGameQnaId === null) {
			$this->gameQnaId = null;
			return;
		}

		// verify the gameQnaId is positive
		if($newGameQnaId <= 0) {
			throw(new \RangeException("gameQnaId is not positive"));
		}

		// convert and store the gameQnaId
		$this->gameQnaId = $newGameQnaId;
	}

	/**
	 * accessor method for gameQnaGameId
	 * @return int value of gameQnaGameId; foreign key
	 **/
	public function getGameQnaGameId() {
		return($this->gameQnaGameId);
	}

	/**
	 * mutator method for gameQnaGameId
	 *
	 * @param int $newGameQnaGameId new value of gameQnaGameId
	 * @throws \RangeException if $newGameQnaGameId is not positive
	 * @throws \TypeError if $newGameQnaGameId is not an integer
	 **/
	public function setGameQnaGameId(int $newGameQnaGameId) {
		// verify the gameQnaGameId is positive
		if($newGameQnaGameId <= 0) {
			throw(new \RangeException("gameQnaGameId is not positive"));
		}

		// convert and store the gameQnaGameId
		$this->gameQnaGameId = $newGameQnaGameId;
	}

	/**
	 * accessor method for gameQnaQnaId
	 * @return int value of gameQnaQnaId; foreign key
	 **/
	public function getGameQnaQnaId() {
		return($this->gameQnaQnaId);
	}

	/**
	 * mutator method for gameQnaQnaId
	 *
	 * @param int $newGameQnaQnaId new value of gameQnaQnaId
	 * @throws \RangeException if $newGameQnaQnaId is not positive
	 * @throws \TypeError if $newGameQnaQnaId is not an integer
	 **/
	public function setGameQnaQnaId(int $newGameQnaQnaId) {
		// verify the gameQnaGameId is positive
		if($newGameQnaQnaId <= 0) {
			throw(new \RangeException("gameQnaQnaId is not positive"));
		}

		// convert and store the gameQnaGameId
		$this->gameQnaQnaId = $newGameQnaQnaId;
	}

	/**
	 * inserts this GameQna in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		// enforce the GameQnaId is null
		if($this->gameQnaId !== null) {
			throw(new \PDOException("GameQna already exists"));
		}

		// create query template
		$query = "INSERT INTO gameQna(gameQnaGameId, gameQnaQnaId) VALUES(:gameQnaGameId, :gameQnaQnaId)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["gameQnaGameId" => $this->gameQnaGameId, "gameQnaQnaId" => $this->gameQnaQnaId];
		$statement->execute($parameters);

		// update the null gameQnaId with what mySQL just gave us
		$this->gameQnaId = intval($pdo->lastInsertId());
	}

	/**
	 * deletes this GameQna from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		// enforce the gameQnaId is not null
		if($this->gameQnaId === null) {
			throw(new \PDOException("unable to delete a gameQna that does not exist"));
		}

		// create a query template
		$query = "DELETE FROM gameQna WHERE gameQnaId = :gameQnaId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["gameQnaId" => $this->gameQnaId];
		$statement->execute($parameters);
	}

	/**
	 * gets the gameQna by GameQnaId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $gameQnaId gameQnaId to search for
	 * @return GameQna|null GameQna found or null if not found
	 * @throws \PDOException when mySQL relate errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getGameQnaByGameQnaId(\PDO $pdo, $gameQnaId) {
		// sanitize the gameQnaId before searching
		if($gameQnaId <= 0) {
			throw(new \PDOException("gameQnaId is not positive"));
		}

		// create query template
		$query = "SELECT gameQnaId, gameQnaGameId, gameQnaQnaId FROM gameQna WHERE gameQnaId = :gameQnaId";
		$statement = $pdo->prepare($query);

		// bind the gameQnaId to the place holder in the template
		$parameters = array("gameQnaId" => $gameQnaId);
		$statement->execute($parameters);

		// grab the gameQna from mySQL
		try {
			$gameQna = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$gameQna = new GameQna($row["gameQnaId"], $row["gameQnaGameId"], $row["gameQnaQnaId"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted throw it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($gameQna);
	}

	/**
	 * gets gameQna by gameQnaGameId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $gameQnaGameId gameQnaGameId to search for
	 * @return \SplFixedArray SplFixedArray of gameQnaGameId's found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getGameQnaByGameQnaGameId(\PDO $pdo, int $gameQnaGameId) {
		// sanitize the gameQnaGameId before searching
		if($gameQnaGameId <= 0) {
			throw(new \PDOException("gameQnaGameId is not positive"));
		}

		// create query template
		$query = "SELECT gameQnaId, gameQnaGameId, gameQnaQnaId FROM gameQna WHERE gameQnaGameId = :gameQnaGameId";
		$statement = $pdo->prepare($query);

		// bind the gameQnaGameId to the placeholder in the template
		$parameters = array("gameQnaGameId" => $gameQnaGameId);
		$statement->execute($parameters);

		// build an array of gameQna's
		$gameQnas = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$gameQna = new GameQna($row["gameQnaId"], $row["gameQnaGameId"], $row["gameQnaQnaId"]);
				$gameQnas[$gameQnas->key()] = $gameQna;
				$gameQnas->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($gameQnas);
	}

	/**
	 * gets gameQna by gameQnaQnaId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $gameQnaQnaId gameQnaQnaId to search for
	 * @return \SplFixedArray SplFixedArray of gameQnaQnaId's found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getGameQnaByGameQnaQnaId(\PDO $pdo, int $gameQnaQnaId) {
		// sanitize the gameQnaGameId before searching
		if($gameQnaQnaId <= 0) {
			throw(new \PDOException("gameQnaQnaId is not positive"));
		}

		// create query template
		$query = "SELECT gameQnaId, gameQnaGameId, gameQnaQnaId FROM gameQna WHERE gameQnaQnaId = :gameQnaQnaId";
		$statement = $pdo->prepare($query);

		// bind the gameQnaQnaId to the placeholder in the template
		$parameters = array("gameQnaQnaId" => $gameQnaQnaId);
		$statement->execute($parameters);

		// build an array of gameQna's
		$gameQnas = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$gameQna = new GameQna($row["gameQnaId"], $row["gameQnaGameId"], $row["gameQnaQnaId"]);
				$gameQnas[$gameQnas->key()] = $gameQna;
				$gameQnas->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($gameQnas);
	}

	/**
	 * gets all gameQna's
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of gameQna's found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllGameQnas(\PDO $pdo) {
		// create query template
		$query = "SELECT gameQnaId, gameQnaGameId, gameQnaQnaId FROM gameQna";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of gameQna's
		$gameQnas = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$gameQna = new GameQna($row["gameQnaId"], $row["gameQnaGameId"], $row["gameQnaQnaId"]);
				$gameQnas[$gameQnas->key()] = $gameQna;
				$gameQnas->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($gameQnas);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		return($fields);
	}
}