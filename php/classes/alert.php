<?php

require_once("filter.php");

/**
 * This class manages the alert levels used to track student progress.
 *
 * There are four levels: success (green), info (yellow), warning (orange), and danger (red)
 *
 * @author Skyler Rexroad
 */
class Alert implements JsonSerializable {

	/**
	 * Primary key of the alert
	 *
	 * @var int $alertId
	 */
	private $alertId;

	/**
	 * Level of the alert
	 *
	 * @var string $alertLevel
	 */
	private $alertLevel;

	/**
	 * Name of the CSS class to use for the alert
	 *
	 * @var string $alertClassName
	 */
	private $alertClassName;

	/**
	 * Alert constructor.
	 * @param int $alertId Primary key of the alert
	 * @param string $alertLevel Level of the alert
	 * @param string $alertClassName Name of the CSS class to use for the alert
	 * @throws RangeException
	 * @throws InvalidArgumentException
	 * @throws Exception
	 */
	public function __construct($alertId, $alertLevel, $alertClassName) {
		try {
			$this->setAlertId($alertId);
			$this->setAlertLevel($alertLevel);
			$this->setAlertClassName($alertClassName);
		} catch(RangeException $range) {
			throw new RangeException($range->getMessage(), 0, $range);
		} catch(InvalidArgumentException $invalidArgument) {
			throw new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument);
		} catch(Exception $exception) {
			throw new Exception($exception->getMessage(), 0, $exception);
		}
	}

	/**
	 * Accesses the alert level
	 *
	 * @return string
	 */
	public function getAlertLevel() {
		return $this->alertLevel;
	}

	/**
	 * Mutates the alert level
	 *
	 * @param string $alertLevel
	 */
	public function setAlertLevel($alertLevel) {
		$this->alertLevel = Filter::filterString($alertLevel, "Alert level", 32);
	}

	/**
	 * Accesses the alert class name
	 * @return string
	 */
	public function getAlertClassName() {
		return $this->alertLevel;
	}

	/**
	 * Mutates the alert class name
	 *
	 * @param string $alertClassName
	 */
	public function setAlertClassName($alertClassName) {
		$this->alertClassName = Filter::filterString($alertClassName, "Alert class name", 16);
	}

	/**
	 * Accesses the alert ID
	 *
	 * @return int
	 */
	public function getAlertId() {
		return $this->alertId;
	}

	/**
	 * Mutates the alert ID
	 *
	 * @param int $alertId
	 */
	public function setAlertId($alertId) {
		$this->alertId = Filter::filterInt($alertId, "Alert ID", true);
	}

	/**
	 * Creates a json object out of this object
	 */
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		return ($fields);
	}

	/**
	 * Gets the alert by alert ID
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param int $alertId Alert ID to search for
	 * @return Alert|null $alert null if not found, Alert if found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getAlertByAlertId(PDO $pdo, $alertId) {
		// Sanitize the ID before searching
		try {
			$alertId = Filter::filterInt($alertId, "Alert ID");
			// echo "ID: " . $alertId . PHP_EOL;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT alertId, alertLevel, alertClassName FROM alert WHERE alertId = :alertId";
		$statement = $pdo->prepare($query);

		$parameters = array("alertId" => $alertId);
		$statement->execute($parameters);

		// Grab the alert from MySQL
		try {
			$alert = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false) {
				$alert = new Alert($row["alertId"], $row["alertLevel"], $row["alertClassName"]);
			}
		} catch(PDOException $pdoException) {
			throw new PDOException($pdoException->getMessage(), 0, $pdoException);
		}

		return $alert;
	}

	/**
	 * Gets the alert by alert level
	 *
	 * @param PDO $pdo PDO connection
	 * @param string $alertLevel Alert level to search for
	 * @return Alert|null $alert null if not found, Alert if found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getAlertByAlertLevel(PDO $pdo, $alertLevel) {
		// Sanitize the alert level before searching
		try {
			$alertLevel = Filter::filterString($alertLevel, "Alert level", 32);
			// echo "Level: " . $alertLevel . PHP_EOL;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT alertId, alertLevel, alertClassName FROM alert WHERE alertLevel LIKE :alertLevel";
		$statement = $pdo->prepare($query);

		$parameters = array("alertLevel" => $alertLevel);
		$statement->execute($parameters);

		// Grab the alert from MySQL
		try {
			$alert = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false) {
				$alert = new Alert($row["alertId"], $row["alertLevel"], $row["alertClassName"]);
			}
		} catch(Exception $exception) {
			throw new PDOException($exception->getMessage(), 0, $exception);
		}

		return $alert;
	}

	public static function getAlertByClassName(PDO $pdo, $alertClassName) {
		// Sanitize the alert level before searching
		try {
			$alertClassName = Filter::filterString($alertClassName, "Alert class name", 16);
			// echo "Class name: " . $alertClassName . PHP_EOL;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT alertId, alertLevel, alertClassName FROM alert WHERE alertClassName LIKE :alertClassName";
		$statement = $pdo->prepare($query);

		$parameters = array("alertClassName" => $alertClassName);
		$statement->execute($parameters);

		// Grab the alert from MySQL
		try {
			$alert = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false) {
				$alert = new Alert($row["alertId"], $row["alertClassName"], $row["alertClassName"]);
			}
		} catch(Exception $exception) {
			throw new PDOException($exception->getMessage(), 0, $exception);
		}

		return $alert;
	}

	/**
	 * Gets all alerts
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @return SplFixedArray|null Array of alerts found
	 * @throws PDOException when MySQL related errors occur
	 */
	public static function getAllAlerts(PDO $pdo) {
		// Create query template
		$query = "SELECT alertId, alertLevel, alertClassName FROM alert";
		$statement = $pdo->prepare($query);
		$statement->execute();

		$alerts = [];
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$alert = new Alert($row["alertId"], $row["alertLevel"], $row["alertClassName"]);
				$alerts[$alert->getAlertId()] = $alert;
			} catch(Exception $exception) {
				throw new PDOException($exception->getMessage(), 0, $exception);
			}
		}

		return $alerts;
	}

}