<?php
require_once("autoloader.php");

/**
 * Student Class Container
 *
 * This is a container for student data. All data is imported from Active Directory and cached in mySQL.
 * This stores all the static data about the student that doesn't change unless the student changes their name.
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class Student implements JsonSerializable {
	/**
	 * student id of this Student; this is the primary key. It must be exactly 9 digits long
	 * @var string $studentId
	 **/
	private $studentId;
	/**
	 * student cohort id; this is a foreign key to Cohort
	 * @var int $studentCohortId
	 **/
	private $studentCohortId;
	/**
	 * student Lumens class id
	 * @var int $studentLumensClassId
	 **/
	private $studentLumensClassId;
	/**
	 * student name from Active Directory
	 * @var string $studentName
	 **/
	private $studentName;
	/**
	 * student username from Active Directory
	 * @var string $studentUsername
	 **/
	private $studentUsername;
	/**
	 *  Slack user name
	 *
	 * @var mixed string or null $attendanceSlackUserName;
	 */
	private $studentSlackUsername;

	/**
	 * constructor for this Student
	 *
	 * @param string $newStudentId new value of student id
	 * @param int $newStudentCohortId new value of student cohort id
	 * @param int $newStudentLumensClassId new value of Lumens class id
	 * @param string $newStudentName new value of student name
	 * @param string $newStudentUsername new value of student username
	 * @param string|null $newStudentSlackUsername new value of slack username
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws Exception if some other exception occurs
	 **/
	public function __construct(string $newStudentId, int $newStudentCohortId, int $newStudentLumensClassId, string $newStudentName, string $newStudentUsername, string $newStudentSlackUsername = null) {
		try {
			$this->setStudentId($newStudentId);
			$this->setStudentCohortId($newStudentCohortId);
			$this->setStudentLumensClassId($newStudentLumensClassId);
			$this->setStudentName($newStudentName);
			$this->setStudentUsername($newStudentUsername);
			$this->setStudentSlackUsername($newStudentSlackUsername);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for student id
	 *
	 * @return string value of student id
	 **/
	public function getStudentId() {
		return($this->studentId);
	}

	/**
	 * mutator method for student id
	 *
	 * @param string $newStudentId new value of student id
	 * @throws InvalidArgumentException if student id is not exactly 9 digits long
	 **/
	public function setStudentId(string $newStudentId) {
		// ensure the student id is exactly 9 numeric digits
		if(preg_match("/^\d{9}$/", $newStudentId) !== 1) {
			throw(new InvalidArgumentException("not a valid student id"));
		}

		// we're storing as a string since most student ids have a leading zero
		// and we need to preserve the leading zero for matching against Active Directory
		$this->studentId = $newStudentId;
	}

	/**
	 * accessor method for student cohort id
	 *
	 * @return int value of cohort id
	 **/
	public function getStudentCohortId() {
		return($this->studentCohortId);
	}

	/**
	 * mutator method for student cohort id
	 *
	 * @param int $newStudentCohortId new value of cohort id
	 * @throws InvalidArgumentException if $newStudentCohortId is not an integer
	 * @throws RangeException if $newStudentCohortId is not positive
	 * @throws Exception if some other exception occurs
	 **/
	public function setStudentCohortId(int $newStudentCohortId) {
		try {
			$newStudentCohortId = Filter::filterInt($newStudentCohortId, "student cohort id");
			$this->studentCohortId = $newStudentCohortId;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for student Lumens class id
	 *
	 * @return string value of student Lumens class id
	 **/
	public function getStudentLumensClassId() {
		return($this->studentLumensClassId);
	}

	/**
	 * mutator method for student lumens class id
	 *
	 * @param int $newStudentLumensClassId new value of lumens class id
	 * @throws InvalidArgumentException if $newStudentCohortId is not an integer
	 * @throws RangeException if $newStudentCohortId is not positive
	 * @throws Exception if some other exception occurs
	 **/
	public function setStudentLumensClassId(int $newStudentLumensClassId) {
		try {
			$newStudentLumensClassId = Filter::filterInt($newStudentLumensClassId, "student lumens class id");
			$this->studentLumensClassId = $newStudentLumensClassId;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for student name
	 *
	 * @return string value of student name
	 **/
	public function getStudentName() {
		return($this->studentName);
	}

	/**
	 * mutator method for student name
	 *
	 * @param string $newStudentName new value of student name
	 * @throws InvalidArgumentException if $newStudentName is not a valid string
	 * @throws RangeException if $newStudentName is too large
	 * @throws Exception if some other exception occurs
	 **/
	public function setStudentName(string $newStudentName) {
		try {
			$newStudentName = Filter::filterString($newStudentName, "student name", 128);
			$this->studentName = $newStudentName;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for student username
	 *
	 * @return string value of student username
	 **/
	public function getStudentUsername() {
		return($this->studentUsername);
	}

	/**
	 * mutator method for student username
	 *
	 * @param string $newStudentUsername new value of student username
	 * @throws InvalidArgumentException if $newStudentUsername is not a valid string
	 * @throws RangeException if $newStudentUsername is too large
	 * @throws Exception if some other exception occurs
	 **/
	public function setStudentUsername(string $newStudentUsername) {
		try {
			$newStudentUsername = Filter::filterString($newStudentUsername, "student username", 20);
			$this->studentUsername = $newStudentUsername;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * Accessor for attendance slack user name
	 *
	 * @return string
	 */
	public function getStudentSlackUserame() {
		return $this->studentSlackUsername;
	}

	/**
	 * mutator method for student slack user name
	 *
	 * @param string $newSlackUsername new value of student slack username
	 * @throws InvalidArgumentException if $newStudentUsername is not a valid string
	 * @throws RangeException if $newStudentUsername is too large
	 * @throws Exception if some other exception occurs
	 **/
	public function setStudentSlackUsername(string $newSlackUsername = null) {
		try {
			// allow slack username to be null
			if($newSlackUsername === null) {
				$this->studentSlackUsername = null;
				return;
			}

			$newSlackUsername = Filter::filterString($newSlackUsername, "student username", 20);
			$this->studentUsername = $newSlackUsername;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * inserts this Student into mySQL
	 *
	 * @param PDO $pdo PDO connection object
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function insert(PDO $pdo) {
		// create query template
		$query = "INSERT INTO student(studentId, studentCohortId, studentLumensClassId, studentName, studentUsername, studentSlackUsername) VALUES(:studentId, :studentCohortId, :studentLumensClassId, :studentName, :studentUsername, :studentSlackUsername)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["studentId" => $this->studentId, "studentCohortId" => $this->studentCohortId, "studentLumensClassId" => $this->studentLumensClassId, "studentName" => $this->studentName, "studentUsername" => $this->studentUsername, "studentSlackUsername" => $this->studentSlackUsername];
		$statement->execute($parameters);
	}

	/**
	 * gets the Student by student id
	 *
	 * @param PDO $pdo PDO connection object
	 * @param string $studentId student id to search for
	 * @return null|Student Student found or null if not found
	 **/
	public static function getStudentByStudentId(PDO $pdo, string $studentId) {
		// ensure the student id is exactly 9 numeric digits
		if(preg_match("/^\d{9}$/", $studentId) !== 1) {
			throw(new PDOException("not a valid student id"));
		}

		// create query template
		$query = "SELECT studentId, studentCohortId, studentLumensClassId, studentName, studentUsername, studentSlackUsername FROM student WHERE studentId = :studentId";
		$statement = $pdo->prepare($query);

		// bind the student id to the place holder in the template
		$parameters = ["studentId" => $studentId];
		$statement->execute($parameters);

		// grab the student from mySQL
		try {
			$student = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$student = new Student($row["studentId"], $row["studentCohortId"], $row["studentLumensClassId"], $row["studentName"], $row["studentUsername"], $row["studentSlackUsername"]);
			}
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return($student);
	}

	/**
	 * gets Students by student cohort id
	 *
	 * @param PDO $pdo PDO connection object
	 * @param int $studentCohortId student cohort id to search for
	 * @return array associative array of all Students found for this student cohort id, indexed by student id
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getStudentByStudentCohortId(PDO $pdo, $studentCohortId) {
		try {
			$studentCohortId = Filter::filterInt($studentCohortId, "student cohort id");
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT studentId, studentCohortId, studentLumensClassId, studentName, studentUsername, studentSlackUsername FROM student WHERE studentCohortId = :studentCohortId ORDER BY studentName";
		$statement = $pdo->prepare($query);

		// bind the student cohort id to the place holder in the template
		$parameters = ["studentCohortId" => $studentCohortId];
		$statement->execute($parameters);

		// build an array of students
		$students = [];
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$student = new Student($row["studentId"], $row["studentCohortId"], $row["studentLumensClassId"], $row["studentName"], $row["studentUsername"], $row["studentSlackUsername"]);
				$students[$student->getStudentId()] = $student;
			} catch(Exception $exception) {
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($students);
	}

	/**
	 * gets the Student by student Lumens class id
	 *
	 * @param PDO $pdo PDO connection object
	 * @param string $studentLumensClassId student Lumens class id to search for
	 * @return null|Student Student found or null if not found
	 **/
	public static function getStudentsByStudentLumensClassId(PDO $pdo, $studentLumensClassId) {
		// ensure the Lumens class id is exactly 5 numeric digits
		if(preg_match("/^\d{5}$/", $studentLumensClassId) !== 1) {
			throw(new PDOException("not a valid student Lumens class id"));
		}

		// create query template
		$query = "SELECT studentId, studentCohortId, studentLumensClassId, studentName, studentUsername, studentSlackUserName FROM student WHERE studentLumensClassId = :studentLumensClassId";
		$statement = $pdo->prepare($query);

		// bind the student Lumens class id to the place holder in the template
		$parameters = ["studentLumensClassId" => $studentLumensClassId];
		$statement->execute($parameters);

		// build an array of students
		$students = [];
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$student = new Student($row["studentId"], $row["studentCohortId"], $row["studentLumensClassId"], $row["studentName"], $row["studentUsername"], $row["studentSlackUsername"]);
				$students[$student->getStudentId()] = $student;
			} catch(Exception $exception) {
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($students);
	}

	/**
	 * gets an index of alert levels for all students in this cohort
	 *
	 * @param PDO $pdo PDO connection object
	 * @param int $cohortId cohort id to search for
	 * @return array associative array mapping student ids to alert level ids
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getStudentAlertLevelsByCohortId(PDO $pdo, $cohortId) {
		try {
			$cohortId = Filter::filterInt($cohortId, "cohort id");
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "CALL getAlertLevelByCohort(:cohortId)";
		$statement = $pdo->prepare($query);

		// bind the cohort id to the place holder in the template
		$parameters = ["cohortId" => $cohortId];
		$statement->execute($parameters);

		$alertLevelMap = [];
		while(($row = $statement->fetch()) !== false) {
			$alertLevelMap[$row["studentId"]] = intval($row["alertLevelId"]);
		}
		return($alertLevelMap);
	}

	/**
	 * specifies which fields will be returned in a json_serialize()
	 *
	 * @return array associative array of which fields will be returned
	 **/
	public function jsonSerialize() {
		return(get_object_vars($this));
	}
}