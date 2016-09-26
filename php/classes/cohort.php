<?php
require_once("autoloader.php");

/**
 * Cohort Class Container
 *
 * This is a container for the cohort data. It is read only in mySQL and populated directly from seed data.
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class Cohort implements JsonSerializable {
	/**
	 * id for this Cohort; this is the primary key
	 * @var int $cohortId
	 **/
	private $cohortId;
	/**
	 * start date for this Cohort
	 * @var DateTime $cohortStartDate
	 **/
	private $cohortStartDate;

	/**
	 * constructor for this Cohort
	 *
	 * @param int $newCohortId new value of cohort id
	 * @param DateTime|string $newCohortStartDate new value of cohort start date
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws Exception if some other exception occurs
	 **/
	public function __construct($newCohortId, $newCohortStartDate) {
		try {
			$this->setCohortId($newCohortId);
			$this->setCohortStartDate($newCohortStartDate);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for cohort id
	 *
	 * @return int value of cohort id
	 **/
	public function getCohortId() {
		return($this->cohortId);
	}

	/**
	 * mutator method for cohort id
	 *
	 * @param int $newCohortId new value of cohort id
	 * @throws InvalidArgumentException if $newCohortId is not an integer
	 * @throws RangeException if $newCohortId is not positive
	 * @throws Exception if some other exception occurs
	 **/
	public function setCohortId($newCohortId) {
		try {
			$newCohortId = Filter::filterInt($newCohortId, "cohort id");
			$this->cohortId = $newCohortId;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for cohort start date
	 *
	 * @return DateTime value of cohort start date
	 **/
	public function getCohortStartDate() {
		return($this->cohortStartDate);
	}

	/**
	 * mutator method for cohort start date
	 *
	 * @param DateTime|int $newCohortStartDate new value of cohort start date
	 * @throws InvalidArgumentException if $newCohortStartDate is not a parseable date
	 * @throws RangeException if $newCohortStartDate is not a valid date
	 * @throws Exception if some other exception occurs
	 **/
	public function setCohortStartDate($newCohortStartDate) {
		// if it comes in as a string, assume it comes as Y-m-d (without the H:i:s)
		// so, we tack midnight on to ensure the rest of the date is correct
		if(is_string($newCohortStartDate) === true) {
			$newCohortStartDate = "$newCohortStartDate 00:00:00";
		}

		try {
			$newCohortStartDate = Filter::filterDate($newCohortStartDate, "cohort start date");
			$this->cohortStartDate = $newCohortStartDate;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * gets cohort by cohort id
	 *
	 * @param PDO $pdo PDO connection object
	 * @param int $cohortId cohort id to search for
	 * @return Cohort|null Cohort found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getCohortByCohortId(PDO $pdo, $cohortId) {
		try {
			$cohortId = Filter::filterInt($cohortId, "cohort id");
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT cohortId, cohortStartDate FROM cohort WHERE cohortId = :cohortId";
		$statement = $pdo->prepare($query);

		// bind the cohort id to the place holder in the template
		$parameters = ["cohortId" => $cohortId];
		$statement->execute($parameters);

		// grab the cohort from mySQL
		try {
			$cohort = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$cohort = new Cohort($row["cohortId"], $row["cohortStartDate"]);
			}
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return($cohort);
	}

	/**
	 * gets current cohort
	 *
	 * @param PDO $pdo PDO connection object
	 * @return Cohort|null Cohort found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getCurrentCohort(PDO $pdo) {

		// create query template and execute
		$query = "SELECT cohortId, cohortStartDate FROM cohort WHERE  cohortStartDate <= NOW() ORDER BY cohortStartDate DESC LIMIT 1";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// grab the cohort from mySQL
		try {
			$cohort = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$cohort = new Cohort($row["cohortId"], $row["cohortStartDate"]);
			}
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return($cohort);
	}

	/**
	 * gets all Cohorts
	 *
	 * @param PDO $pdo PDO connection object
	 * @return SplFixedArray all Cohorts found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getAllCohorts(PDO $pdo) {
		// create query template
		$query = "SELECT cohortId, cohortStartDate FROM cohort";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of cohorts
		$cohorts = new SplFixedArray($statement->rowCount());
		while(($row = $statement->fetch()) !== false) {
			try {
				$cohort = new Cohort($row["cohortId"], $row["cohortStartDate"]);
				$cohorts[$cohorts->key()] = $cohort;
				$cohorts->next();
			} catch(Exception $exception) {
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($cohorts);
	}

	/**
	 * specifies which fields will be returned in a json_serialize()
	 *
	 * @return array associative array of which fields will be returned
	 **/
	public function jsonSerialize() {
		$angularDate = $this->cohortStartDate->format("U") * 1000;
		$fields = ["cohortId" => $this->cohortId, "cohortStartDate" => $angularDate];
		return($fields);
	}
}