<?php

require_once("autoloader.php");

/**
 * This class manages attendance and daily student sign in.
 *
 *
 * @author Derek Mauldin
 */


class Attendance implements JsonSerializable {
	/**
	 * Primary key for Attendance
	 *
	 * @var int $attendanceId
	 */
	private $attendanceId;

	/**
	 * Foreign key references Cohort
	 *
	 * @var int $attendanceCohortId
	 */
	private $attendanceCohortId;

	/**
	 * Foreign key references Student
	 *
	 * @var string $attendanceStudentId
	 */
	private $attendanceStudentId;

	/**
	 * Class date that a student signs in for attendance
	 *
	 * @var DateTime $attendanceDate
	 */
	private $attendanceDate;

	/**
	 * Actual date and time the student signs in for attendance
	 *
	 * @var DateTime $attendanceCreateDateTime
	 */
	private $attendanceCreateDateTime;

	/**
	 * IP address the student is logging in from (stored in network form)
	 *
	 * @var string $attendanceIpAddress
	 */
	private $attendanceIpAddress;

	/**
	 * Browser info for student signing in
	 *
	 * @var string $attendanceBrowser
	 */
	private $attendanceBrowser;

	/**
	 * Number of hours student attended class for the particular day
	 *
	 * @var float $attendanceHours
	 */
	private $attendanceHours;

	/**
	 * Name of bridge officer that overrides a sign in
	 *
	 * @var string $attendanceOverrideUsername
	 */
	private $attendanceOverrideUsername;




	/**
	 * Attendance constructor.
	 *
	 * @param int $attendanceId
	 * @param int $attendanceCohortId
	 * @param string $attendanceStudentId
	 * @param DateTime|string $attendanceDate
	 * @param DateTime|string $attendanceCreateDateTime
	 * @param string $attendanceIpAddress
	 * @param string $attendanceBrowser
	 * @param float $attendanceHours
	 * @param string $attendanceOverrideUsername
	 * @throws RangeException
	 * @throws InvalidArgumentException
	 * @throws Exception
	 */
	public function __construct(int $attendanceId = null, int $attendanceCohortId, string $attendanceStudentId, $attendanceDate, $attendanceCreateDateTime = null, string $attendanceIpAddress, string $attendanceBrowser, float $attendanceHours, string $attendanceOverrideUsername = null) {
		try {
			$this->setAttendanceId($attendanceId);
			$this->setAttendanceCohortId($attendanceCohortId);
			$this->setAttendanceStudentId($attendanceStudentId);
			$this->setAttendanceDate($attendanceDate);
			$this->setAttendanceCreateDateTime($attendanceCreateDateTime);
			$this->setAttendanceIPaddress($attendanceIpAddress);
			$this->setAttendanceBrowser($attendanceBrowser);
			$this->setAttendanceHours($attendanceHours);
			$this->setAttendanceOverrideUserName($attendanceOverrideUsername);
		} catch(RangeException $range) {
			throw new RangeException($range->getMessage(), 0, $range);
		} catch(InvalidArgumentException $invalidArgument) {
			throw new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument);
		} catch(Exception $exception) {
			throw new Exception($exception->getMessage(), 0, $exception);
		}
	}

	/**
	 * Accessor for attendance ID
	 *
	 * @return int
	 */
	public function getAttendanceId() {
		return $this->attendanceId;
	}

	/**
	 *
	 * Mutator for attendance ID
	 *
	 * @param int $attendanceID
	 */
	public function setAttendanceId(int $attendanceID = null) {
		if($attendanceID === null) {
			$this->attendanceId = null;
		} else {
			$this->attendanceId = Filter::filterInt($attendanceID, "Attendance ID");
		}
	}

	/**
	 * Accessor for attendance cohort ID
	 *
	 * @return int
	 */
	public function getAttendanceCohortId() {
		return $this->attendanceCohortId;
	}

	/**
	 * Mutator for attendance cohort ID
	 *
	 * @param int $cohortId
	 */
	public function setAttendanceCohortId(int $cohortId) {
		$this->attendanceCohortId = Filter::filterInt($cohortId, "Attendance Cohort ID");
	}

	/**
	 * Accessor for attendance student ID
	 *
	 * @return int
	 */
	public function getAttendanceStudentId() {
		return $this->attendanceStudentId;
	}

	/**
	 * Mutator for attendance student ID
	 *
	 * @param string $attendanceStudentId
	 */
	public function setAttendanceStudentId(string $attendanceStudentId) {
		// ensure the student id is exactly 9 numeric digits
		if(preg_match("/^\d{9}$/", $attendanceStudentId) !== 1) {
			throw(new InvalidArgumentException("not a valid student id", 400));
		}

		// we're storing as a string since most student ids have a leading zero
		// and we need to preserve the leading zero for matching against Active Directory
		$this->attendanceStudentId = $attendanceStudentId;
	}

	/**
	 * Accessor for $attendanceDate
	 *
	 * @return DateTime
	 */
	public function getAttendanceDate() {
		return $this->attendanceDate;
	}

	/**
	 * Mutator for $attendanceDate
	 *
	 * @param mixed DateTime|string $attendanceDate
	 */
	public function setAttendanceDate($attendanceDate = null) {
		if($attendanceDate === null) {
			$this->attendanceDate = null;
		} else {
			$this->attendanceDate = Filter::filterDate($attendanceDate, "Attendance Date", true);
		}
	}

	/**
	 * Accessor for $attendanceCreateDateTime
	 *
	 * @return DateTime
	 */
	public function getAttendanceCreateDateTime() {
		return $this->attendanceCreateDateTime;
	}

	/**
	 * Mutator for $attendanceCreateDateTime
	 *
	 * Sets $attendanceCreateDateTime to the current date and time
	 * @param DateTime $attendanceCreateDateTime
	 */
	public function setAttendanceCreateDateTime(DateTime $attendanceCreateDateTime = null) {
		if($attendanceCreateDateTime === null) {
			$this->attendanceCreateDateTime = null;
		} else {
			$this->attendanceCreateDateTime = Filter::filterDate($attendanceCreateDateTime, "attendanceCreateDateTime", true);
		}
	}

	/**
	 * Accessor for IP address
	 *
	 * @param $binaryMode
	 * @return int
	 */
	public function getAttendanceIPaddress($binaryMode = false) {
		if($binaryMode === true) {
			return($this->attendanceIpAddress);
		}
		return(inet_ntop($this->attendanceIpAddress));
	}

	/**
	 * Mutator for IP address
	 *
	 * @param string $ipAddress
	 */
	public function setAttendanceIPaddress(string $ipAddress) {
		// detect the IP's format and assign it in binary mode
		if(inet_pton($ipAddress) !== false) {
			$this->attendanceIpAddress = inet_pton($ipAddress);
		} else if(inet_ntop($ipAddress) !== false) {
			$this->attendanceIpAddress = $ipAddress;
		} else {
			throw(new InvalidArgumentException("invalid IP address"));
		}
	} 

	/**
	 * Accessor for browser info
	 *
	 * @return string
	 */
	public function getAttendanceBrowser() {
		return $this->attendanceBrowser;
	}

	/**
	 * Mutator for browser info
	 *
	 * @param string $browser
	 */
	public function setAttendanceBrowser(string $browser) {
		$this->attendanceBrowser = Filter::filterString($browser, "Attendance Browser");
	}

	/**
	 * Accessor for attendance hours
	 *
	 * @return string
	 */
	public function getAttendanceHours() {
		return $this->attendanceHours;
	}

	/**
	 * Mutator for attendance hours
	 *
	 * @param float $hours
	 */
	public function setAttendanceHours(float $hours) {
		$this->attendanceHours = $hours;
	}

	/**
	 * Accessor for attendance override user name
	 *
	 * @return string
	 */
	public function getAttendanceOverrideUserName() {
		return $this->attendanceOverrideUsername;
	}

	/**
	 * Mutator for attendance override user name
	 *
	 * @param string $username
	 */
	public function setAttendanceOverrideUserName(string $username = null) {
		if($username === null) {
			$this->attendanceOverrideUsername = null;
		} else {
			$this->attendanceOverrideUsername = Filter::filterString($username, "Attendance User Name");
		}
	}
	

	/**
	 * Creates a json object out of this object
	 */
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["attendanceDate"] = $this->attendanceDate->format("U") * 1000;
		$fields["attendanceCreateDateTime"] = $this->attendanceCreateDateTime->format("U") * 1000;
		$fields["attendanceIpAddress"] = inet_ntop($this->attendanceIpAddress);
		return ($fields);
	}


	/**
	 * Inserts an attendance record into the database
	 *
	 *
	 * @param PDO $pdo PDO connection
	 * @throws PDOException when MySQL related errors occur
	 */
	public function insert(PDO $pdo) {
		// Make sure this is a new attendance record
		if($this->attendanceId !== null) {
			throw(new PDOException("Not attendance record.", 400));
		}

		// Create query template
		$query = "INSERT INTO attendance(attendanceCohortId, attendanceStudentId, attendanceDate, attendanceIpAddress, attendanceBrowser, attendanceHours, attendanceOverrideUsername)
                VALUES(:attendanceCohortId, :attendanceStudentId, :attendanceDate, :attendanceIpAddress, :attendanceBrowser, :attendanceHours, :attendanceOverrideUsername)";
		$statement = $pdo->prepare($query);

		// Bind variables to template
		$formattedAttendanceDate = $this->attendanceDate->format("Y-m-d");
		$parameters = array("attendanceCohortId" => $this->attendanceCohortId, "attendanceStudentId" => $this->attendanceStudentId,
								"attendanceDate" => $formattedAttendanceDate, "attendanceIpAddress" => $this->attendanceIpAddress,
								"attendanceBrowser" => $this->attendanceBrowser, "attendanceHours" => $this->attendanceHours,
								"attendanceOverrideUsername" => $this->attendanceOverrideUsername);
		$statement->execute($parameters);

		$this->setAttendanceId(intval($pdo->lastInsertId()));
	}


	/**
	 * Gets an attendance record by ID
	 *
	 * @param PDO $pdo PDO connection
	 * @param int $attendanceId attendance ID to search by
	 * @return Attendance|null null if nothing found, otherwise returns an attendance record
	 */
	public static function getAttendanceByAttendanceId(PDO $pdo, int $attendanceId) {
		// Sanitize the ID before searching
		try {

			$attendanceId = Filter::filterInt($attendanceId, "Attendance ID");

		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT attendanceId, attendanceCohortId, attendanceStudentId, attendanceDate, attendanceCreateDateTime, attendanceIpAddress, attendanceBrowser, attendanceHours, attendanceOverrideUsername
					 FROM attendance WHERE attendanceId = :attendanceId";
		$statement = $pdo->prepare($query);

		$parameters = array("attendanceId" => $attendanceId);
		$statement->execute($parameters);

		// Grab the attendance record from MySQL
		try {
			$attendanceRecord = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false) {
				$attendanceRecord = new Attendance($row["attendanceId"], $row["attendanceCohortId"], $row["attendanceStudentId"],
																DateTime::createFromFormat("Y-m-d", $row["attendanceDate"]), DateTime::createFromFormat("Y-m-d H:i:s", $row["attendanceCreateDateTime"]),
																$row["attendanceIpAddress"], $row["attendanceBrowser"], $row["attendanceHours"], $row["attendanceOverrideUsername"]);
			}
		} catch(PDOException $pdoException) {
			throw new PDOException($pdoException->getMessage(), 0, $pdoException);
		}

		return $attendanceRecord;

	}  // getAttendanceByAttendanceId

	/**
	 * Gets all attendance records for a cohort
	 *
	 * @param PDO $pdo PDO connection
	 * @param int $attendanceCohortId cohort ID to search by
	 * @return SplFixedArray array of attendance records retrieved
	 */
	public static function getAttendanceByCohortId(PDO $pdo, int $attendanceCohortId) {
		// Sanitize the cohort ID
		try {

			$attendanceCohortId = Filter::filterInt($attendanceCohortId, "Attendance Cohort ID");

		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT attendanceId, attendanceCohortId, attendanceStudentId, attendanceDate, attendanceCreateDateTime, attendanceIpAddress, attendanceBrowser, attendanceHours, attendanceOverrideUsername
					 FROM attendance WHERE attendanceCohortId = :attendanceCohortId";
		$statement = $pdo->prepare($query);

		// Bind parameters
		$parameters = array("attendanceCohortId" => $attendanceCohortId);
		$statement->execute($parameters);

		$attendanceRecord = null;
		$attendanceRecords = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$attendanceRecord = new Attendance($row["attendanceId"], $row["attendanceCohortId"], $row["attendanceStudentId"],
																DateTime::createFromFormat("Y-m-d", $row["attendanceDate"]), DateTime::createFromFormat("Y-m-d H:i:s", $row["attendanceCreateDateTime"]), $row["attendanceIpAddress"],
																$row["attendanceBrowser"], $row["attendanceHours"], $row["attendanceOverrideUsername"]);
				$attendanceRecords[$attendanceRecords->key()] = $attendanceRecord;
				$attendanceRecords->next();
			} catch(Exception $exception) {
				throw new PDOException($exception->getMessage(), 0, $exception);
			}
		}
		return $attendanceRecords;

	}  // getAttendanceByCohortId


	/**
	 * Gets all attendance records for a student
	 *
	 * @param PDO $pdo PDO connection
	 * @param int $attendanceStudentId student ID to search by
	 * @return SplFixedArray array of attendance records retrieved
	 */
	public static function getAttendanceByStudentId(PDO $pdo, int $attendanceStudentId) {
		// Sanitize the student ID
		try {

			$attendanceStudentId = Filter::filterInt($attendanceStudentId, "Attendance Student ID");

		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT attendanceId, attendanceCohortId, attendanceStudentId, attendanceDate, attendanceCreateDateTime, attendanceIpAddress, attendanceBrowser, attendanceHours, attendanceOverrideUsername
					 FROM attendance WHERE attendanceStudentId = :attendanceStudentId";
		$statement = $pdo->prepare($query);

		// Bind parameters
		$parameters = array("attendanceStudentId" => $attendanceStudentId);
		$statement->execute($parameters);

		$attendanceRecord = null;
		$attendanceRecords = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$attendanceRecord = new Attendance($row["attendanceId"], $row["attendanceCohortId"], $row["attendanceStudentId"],
																DateTime::createFromFormat("Y-m-d", $row["attendanceDate"]), DateTime::createFromFormat("Y-m-d H:i:s", $row["attendanceCreateDateTime"]),
																$row["attendanceIpAddress"], $row["attendanceBrowser"], $row["attendanceHours"],
																$row["attendanceOverrideUsername"]);
				$attendanceRecords[$attendanceRecords->key()] = $attendanceRecord;
				$attendanceRecords->next();
			} catch(Exception $exception) {
				throw new PDOException($exception->getMessage(), 0, $exception);
			}
		}

		return $attendanceRecords;

	}  // getAttendanceByStudentId

	/**
	 * Gets all attendance records for a specific date
	 *
	 * @param PDO $pdo PDO connection
	 * @param DateTime $attendanceDate - date to search by
	 * @return SplFixedArray array of attendance records retrieved
	 */
	public static function getAttendanceByDate(PDO $pdo, DateTime $attendanceDate) {
		// Sanitize the student ID
		try {

			$attendanceDate = Filter::filterDate($attendanceDate, "Attendance Date");

		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}


		// Create query template
		$query = "SELECT attendanceId, attendanceCohortId, attendanceStudentId, attendanceDate, attendanceCreateDateTime, attendanceIpAddress, attendanceBrowser, attendanceHours, attendanceOverrideUsername
					 FROM attendance WHERE attendanceDate = :attendanceDate";
		$statement = $pdo->prepare($query);

		// Bind parameters
		$formattedDate = $attendanceDate->format("Y-m-d");
		$parameters = array("attendanceDate" => $formattedDate);
		$statement->execute($parameters);

		$attendanceRecord = null;
		$attendanceRecords = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$attendanceRecord = new Attendance($row["attendanceId"], $row["attendanceCohortId"], $row["attendanceStudentId"],
																DateTime::createFromFormat("Y-m-d", $row["attendanceDate"]), DateTime::createFromFormat("Y-m-d H:i:s", $row["attendanceCreateDateTime"]),
																$row["attendanceIpAddress"], $row["attendanceBrowser"], $row["attendanceHours"],
																$row["attendanceOverrideUsername"]);
				$attendanceRecords[$attendanceRecords->key()] = $attendanceRecord;
				$attendanceRecords->next();
			} catch(Exception $exception) {
				throw new PDOException($exception->getMessage(), 0, $exception);
			}
		}

		return $attendanceRecords;

	}  // getAttendanceByDate


	/**
	 * Gets slack user names for all users that still need to sign in for today
	 *
	 *
	 * @param 	PDO $pdo PDO connection
	 * @return array of slack user names
	 * @throws PDOException
	 * @throws InvalidArgumentException
	 * @throws RangeException
	 * @throws Exception
	 */
	public static function getTodaysAttendanceForSlackBot (PDO $pdo) {

		$notSignedInList = [];  // list of slacker user names for students not signed in

		// attendance date is today
		$today = new DateTime('now');
		$today = $today->format("Y-m-d");

		try {
			// get current cohort ID
			$query = "SELECT cohortId FROM cohort WHERE cohortStartDate <= :today ORDER BY cohortId DESC LIMIT 1";
			$statement = $pdo->prepare($query);
			$parameters = ["today" => $today];
			$statement->execute($parameters);
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$currentCohort = $statement->fetch();

			// get list of this cohorts students
			$query = "SELECT studentId, studentSlackUsername FROM student WHERE studentCohortId = :currentCohort ";
			$statement = $pdo->prepare($query);
			$parameters = ["currentCohort" => $currentCohort["cohortId"]];
			$statement->execute($parameters);
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$studentList = [];
			while(($row = $statement->fetch()) !== false) {
				array_push($studentList, $row);
			}

			// get todays sign in list
			$query = "SELECT attendanceStudentId FROM attendance WHERE attendanceDate = :today";
			$statement = $pdo->prepare($query);
			$parameters = ["today" => $today];
			$statement->execute($parameters);
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$attendanceList = [];
			while(($row = $statement->fetch()) !== false) {
				array_push($attendanceList, $row);
			}

		} catch (PDOException $pdoException) {
			throw(new PDOException($pdoException->getMessage(), 0, $pdoException));
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new Exception($exception->getMessage(), 0, $exception));
		}

		if (count($studentList) === count($attendanceList)) { // everyone has signed in for today
			return $notSignedInList;
		}

		// compare list of students to list of students that have signed in
		// set studentId to null in $studentList if the student has already signed in
		foreach($attendanceList as $key => $field) {
			foreach($studentList as $k => $f) {
				if($field["attendanceStudentId"] === $f["studentId"]) {
					$studentList[$k]["studentId"] = null;
				}
			}
		}

		// create list of slack user names for students that still need to sign in
		foreach($studentList as $student) {
			if($student["studentId"] !== null) {
				array_push($notSignedInList, $student["studentSlackUsername"]);
			}
		}
		
		return $notSignedInList;

	} // getTodaysAttendanceForSlackBot


}  // Attendance