<?php
namespace Edu\Cnm\DDCBJeopardy;

/**
 * autoloader function to include other classes
 */
require_once("autoload.php");

/**
 * Class Score
 *
 * @author Robert Engelbert <rob@robertengelbert.com>
 * @author Giles Sandoval <hello@gilessandoval.com>
 * @ver 1.0.0
 */
class Score implements \JsonSerializable {
	/**
	 * scoreGameQnaId this is the foreign key
	 *
	 * @var int $scoreGameQnaId
	 **/
	private $scoreGameQnaId;
	/**
	 * scorePlayerId this is a foreign key
	 *
	 * @var int $scorePlayerId
	 **/
	private $scorePlayerId;
	/**
	 * @var string $scoreFinalJeopardyAnswer
	 **/
	private $scoreFinalJeopardyAnswer;
	/**
	 * @@var int $scoreVal
	 **/
	private $scoreVal;

	/**
	 * Score constructor
	 *
	 * @param int $newScoreGameQnaId
	 * @param int $newScorePlayerId
	 * @param string $newScoreFinalJeopardyAnswer
	 * @param int $newScoreVal
	 * @throws \TypeError if variables are not the correct data type
	 * @throws \InvalidArgumentException for invalid exceptions
	 * @throws \RangeException for exceptions that are out of range
	 * @throws \Exception for all other exceptions
	 */

	public function __construct(int $newScoreGameQnaId, int $newScorePlayerId, string $newScoreFinalJeopardyAnswer, int $newScoreVal) {
		try {
			$this->setScoreGameQnaId($newScoreGameQnaId);
			$this->setScorePlayerId($newScorePlayerId);
			$this->setScoreFinalJeopardyAnswer($newScoreFinalJeopardyAnswer);
			$this->setScoreVal($newScoreVal);
		} catch(\InvalidArgumentException $invalidArgument) {
			//rethrow exception
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			//rethrow exception
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			//rethrow exception
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception) {
			//rethrow exception
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * Accessor for scoreId
	 *
	 * @return int
	 */
	public function getScoreId() {
		return ($this->scoreId);
	}

	/**
	 * Mutator for scoreId
	 *
	 * @param $newScoreId
	 * @throws \TypeError if variables are not the correct data type
	 * @throws \RangeException if scoreId is not valid
	 */
	public function setScoreId(int $newScoreId) {
		//verify scoreId is valid
		if($newScoreId === null) {
			$this->scoreId = null;
			return;
		}
		if($newScoreId <= 0) {
			throw(new \RangeException("ScoreId must be a positive number"));
		}
		//convert and store value
		$this->scoreId = $newScoreId;
	}

	/**
	 * Accessor method for scoreGameId
	 *
	 * @return int
	 */
	public function getScoreGameId() {
		return ($this->scoreGameId);
	}

	/**
	 * Mutator method for scoreGameId
	 *
	 * @param $newScoreGameId
	 * @throws \TypeError if variables are not the correct data type
	 */
	public function setScoreGameId(int $scoreGameId) {
		//convert and store the value
		$this->scoreGameId = $scoreGameId;
	}

	/**
	 * Accessor method for scoreStudentId
	 *
	 * @return int
	 */
	public function getScoreStudentId() {
		return ($this->scoreStudentId);
	}

	/**
	 * Mutator method for scoreStudentId
	 *
	 * @param $newScoreStudentId
	 * @throws \TypeError if variables are not the correct data type
	 */
	public function setScoreStudentId(int $scoreStudentId) {
		//convert and store the value
		$this->scoreStudentId = $scoreStudentId;
	}

	/**
	 * Accessor method for scoreStudentScore
	 *
	 * @return int
	 */
	public function getScoreStudentScore() {
		return ($this->scoreStudentScore);
	}

	/**
	 * Mutator method for scoreStudentScore
	 *
	 * @param int $scoreStudentScore
	 * @throws \TypeError if variables are not the correct data type
	 */
	public function setScoreStudentScore(int $scoreStudentScore) {
		//convert and store the value
		$this->scoreStudentScore = $scoreStudentScore;
	}

	/**
	 * Insert method
	 *
	 * @param \PDO $pdo
	 * @throws \PDOException if scoreId is not null
	 */
	public function insert(\PDO $pdo) {
		if($this->scoreId !== null) {
			throw(new \PDOException("This is a PDOException"));
		}
		//create query template
		$query = "INSERT INTO score(scoreGameId, scoreStudentId, scoreStudentScore)VALUES(:scoreGameId, :scoreStudentId, :scoreStudentScore)";
		$statement = $pdo->prepare($query);

		//bind variables to the place holders in the template
		$parameters = ["scoreGameId" => $this->scoreGameId, "scoreStudentId" => $this->scoreStudentId, "scoreStudentScore" => $this->scoreStudentScore];
		$statement->execute($parameters);

		//update scoreId with what sql returns
		$this->scoreId = intval($pdo->lastInsertId());
	}

	/**
	 * PDO delete function
	 *
	 * @param \PDO $pdo
	 * @throws \PDOException if score is null
	 */
	public function delete(\PDO $pdo) {
		//make sure scoreId is not null
		if($this->scoreId === null) {
			throw(new \PDOException("This Id doesn't exist"));
		}
		//create query template
		$query = "DELETE FROM score WHERE scoreId = :scoreId";
		$statement = $pdo->prepare($query);

		//bind varables to place holders in template
		$parameters = ["scoreId" => $this->scoreId];
		$statement->execute($parameters);
	}

	/**
	 * PDO update function
	 *
	 * @param \PDO $pdo
	 * @throws \PDOException if scoreId dose'nt exist
	 */
	public function update(\PDO $pdo) {
		//make sure scoreId is not null
		if($this->scoreId === null) {
			throw(new \PDOException("This Id does'nt exist"));
		}
		$query = "UPDATE score SET scoreGameId = :scoreGameId, scoreStudentId = :scoreStudentId, scoreStudentScore = :scoreStudentScore WHERE scoreId = :scoreId";
		$statement = $pdo->prepare($query);

		//bind variables to placeholders in template
		$parameters = ["scoreGameId" => $this->scoreGameId, "scoreStudentId" => $this->scoreStudentId, "scoreStudentScore" => $this->scoreStudentScore];
		$statement->execute($parameters);
	}

	/**
	 * getScoreByScoreId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $getScoreByScoreId Id to search for
	 * @throws \TypeError if variables are not the correct data type
	 * @throws \PDOException if database error occurs
	 * @throws \Exception for all other exceptions
	 */
	public static function getScoreByScoreId(\PDO $pdo, int $scoreId) {
		//sanitize scoreId before searching
		if(empty($scoreId) === true) {
			throw(new \PDOException("Enter a number"));
		}
		//create a query template
		$query = "SELECT scoreId, scoreGameId, scoreStudentId, scoreStudentScore FROM score WHERE scoreId = :scoreId";
		$statement = $pdo->prepare($query);

		//bind to values in template
		$parameters = ["scoreId" => $scoreId];
		$statement->execute($parameters);

		try{
			$score = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false){
				$score = new Score($row["scoreId"], $row["scoreGameId"], $row["scoreStudentId"], $row["scoreStudentScore"]);
			}
		}catch(\Exception $exception){
			//rethrow exception
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return $score;
	}
	/**
	 * getScoreByScoreGameId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $getScoreByScoreGameId Id to search for
	 * @throws \TypeError if variables are not the correct data type
	 * @throws \PDOException if data base error occurs
	 * @throws \Exception for all other exception
	 */
	public static function getScoreByScoreGameId(\PDO $pdo, int $scoreGameId){
		//sanitize scoreGameId before searching
		if(empty($scoreGameId) === true){
			throw(new \PDOException("Enter a number"));
		}
		//create a query template
		$query = "SELECT scoreId, scoreGameId, scoreStudentId, scoreStudentScore FROM score WHERE scoreGameId = :scoreGameId";
		$statement = $pdo->prepare($query);

		//bind to values in template
		$parameters = ["scoreGameId" => $scoreGameId];
		$statement->execute($parameters);

		try{
			$score = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false){
				$score = new Score($row["scoreId"], $row["scoreGameId"], $row["scoreStudentId"], $row["scoreStudentScore"]);
			}
		}catch(\Exception $exception){
			//rethrow exception
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return $score;
	}
	/**
	 * getScoreByScoreStudentId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $getScoreByScoreStudentId Id to search for
	 * @throws \TypeError if variables are not the correct data type
	 * @throws \PDOException if data base error occurs
	 * @throws \Exception for all other exception
	 */
	public static function getScoreByScoreStudentId(\PDO $pdo, int $scoreStudentId){
		//sanitize scoreStudentId before searching
		if(empty($scoreStudentId) === true){
			throw(new \PDOException("Enter a number"));
		}
		//create a query template
		$query = "SELECT scoreId, scoreGameId, scoreStudentId, scoreStudentScore FROM score WHERE scoreStudentId = :scoreStudentId";
		$statement = $pdo->prepare($query);

		//bind to values in template
		$parameters = ["scoreStudentId" => $scoreStudentId];
		$statement->execute($parameters);

		try{
			$score = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false){
				$score = new Score($row["scoreId"], $row["scoreGameId"], $row["scoreStudentId"], $row["scoreStudentScore"]);
			}
		}catch(\Exception $exception){
			//rethrow exception
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return $score;
	}
	/**
	 * getScoreByScoreStudentScore
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $getScoreByScoreStudentScore Id to search for
	 * @throws \TypeError if variables are not the correct data type
	 * @throws \PDOException if data base error occurs
	 * @throws \Exception for all other exception
	 */
	public static function getScoreByScoreStudentScore(\PDO $pdo, int $scoreStudentScore){
		//sanitize scoreStudentScore before searching
		if(empty($scoreStudentScore) === true){
			throw(new \PDOException("Enter a number"));
		}
		//create a query template
		$query = "SELECT scoreId, scoreGameId, scoreStudentId, scoreStudentScore FROM score WHERE scoreStudentScore = :scoreStudentScore";
		$statement = $pdo->prepare($query);

		//bind to values in template
		$parameters = ["scoreStudentScore" => $scoreStudentScore];
		$statement->execute($parameters);

		try{
			$score = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false){
				$score = new Score($row["scoreId"], $row["scoreGameId"], $row["scoreStudentId"], $row["scoreStudentScore"]);
			}
		}catch(\Exception $exception){
			//rethrow exception
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return $score;
	}
	/**
	 * test getAllScores
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param $getAllScores Id to serach for
	 * @throws \TypeError if variables are not the correct data type
	 * @throws \PDOException if data base error occurs
	 * @throws \Exception for all other exceptions
	 */
	public static function getAllScores(\PDO $pdo){
		//create a query template
		$query = "SELECT scoreId, scoreGameId, ScoreStudentId, ScoreStudentScore FROM score";
		$statement = $pdo->prepare($query);
		$statement->execute();
		//build an array of scores
		$scores = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false)
			try{
			$score = new Score($row["scoreId"], $row["scoreGameId"], $row["scoreStudentId"], $row["scoreStudentScore"]);
			}catch(\Exception $exception){
				//rethrow exception
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
			return($score);
	}
	/**
	 * Includes all json serialization fields
	 *
	 * @returns array containing all score fields
	 */
	public function jsonSerialize(){
		return (get_object_vars($this));
	}
}