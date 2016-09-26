<?php

require_once("autoloader.php");

/**
 * This class manages comments on student progress.
 *
 * Each class is attached to a student and an alert level.
 *
 * @author Skyler Rexroad
 */
class Comment implements JsonSerializable {

	/**
	 * Primary key of the comment
	 *
	 * @var int $commentId
	 */
	private $commentId;

	/**
	 * Foreign key to Alert
	 *
	 * @var int $commentAlertId
	 */
	private $commentAlertId;

	/**
	 * Foreign key to Cohort
	 *
	 * @var int $commentCohortId
	 */
	private $commentCohortId;

	/**
	 * Foreign key to Student
	 *
	 * @var string $commentStudentId
	 */
	private $commentStudentId;

	/**
	 * When the comment was created
	 *
	 * @var DateTime $commentCreateDate
	 */
	private $commentCreateDate;

	/**
	 * Whether or not the student can see this comment
	 *
	 * @var boolean $commentStudentVisible
	 */
	private $commentStudentVisible;

	/**
	 * The contents of the comment
	 *
	 * @var string $commentText
	 */
	private $commentText;

	/**
	 * Who posted the comment
	 *
	 * @var string $commentUsername
	 */
	private $commentUsername;

	public function __construct($commentId, $commentAlertId, $commentCohortId, $commentStudentId, $commentCreateDate, $commentStudentVisible, $commentText, $commentUsername) {
		try {
			$this->setCommentId($commentId);
			$this->setCommentAlertId($commentAlertId);
			$this->setCommentCohortId($commentCohortId);
			$this->setCommentStudentId($commentStudentId);
			$this->setCommentCreateDate($commentCreateDate);
			$this->setCommentStudentVisible($commentStudentVisible);
			$this->setCommentText($commentText);
			$this->setCommentUsername($commentUsername);
		} catch(RangeException $range) {
			throw new RangeException($range->getMessage(), 0, $range);
		} catch(InvalidArgumentException $invalidArgument) {
			throw new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument);
		} catch(Exception $exception) {
			throw new Exception($exception->getMessage(), 0, $exception);
		}
	}

	/**
	 * Accessor for comment ID
	 *
	 * @return int
	 */
	public function getCommentId() {
		return $this->commentId;
	}

	/**
	 * Mutator for comment ID
	 *
	 * @param int $commentId
	 */
	public function setCommentId($commentId) {
		$this->commentId = Filter::filterInt($commentId, "Comment ID", true);
	}

	/**
 * Accessor for alert ID
 *
 * @return int
 */
	public function getCommentAlertId() {
		return $this->commentAlertId;
	}

	/**
	 * Mutator for alert ID
	 *
	 * @param int $commentAlertId
	 */
	public function setCommentAlertId($commentAlertId) {
		$this->commentAlertId = Filter::filterInt($commentAlertId, "Comment alert ID");
	}

	/**
	 * Accessor for cohort ID
	 *
	 * @return int
	 */
	public function getCommentCohortId() {
		return $this->commentCohortId;
	}

	/**
	 * Mutator for cohort ID
	 *
	 * @param int $commentCohortId
	 */
	public function setCommentCohortId($commentCohortId) {
		$this->commentCohortId = Filter::filterInt($commentCohortId, "Comment cohort ID");
	}

	/**
	 * Accessor for student ID
	 *
	 * @return int
	 */
	public function getCommentStudentId() {
		return $this->commentStudentId;
	}

	/**
	 * Mutator for student ID
	 *
	 * @param int $commentStudentId
	 */
	public function setCommentStudentId($commentStudentId) {
		// ensure the student id is exactly 9 numeric digits
		if(preg_match("/^\d{9}$/", $commentStudentId) !== 1) {
			throw(new InvalidArgumentException("not a valid student id"));
		}

		// we're storing as a string since most student ids have a leading zero
		// and we need to preserve the leading zero for matching against Active Directory
		$this->commentStudentId = $commentStudentId;
	}

	/**
	 * Accessor for comment create date
	 *
	 * @return DateTime
	 */
	public function getCommentCreateDate() {
		return $this->commentCreateDate;
	}

	/**
	 * Mutator for comment create date
	 *
	 * @param DateTime $commentCreateDate
	 */
	public function setCommentCreateDate($commentCreateDate) {
		$this->commentCreateDate = Filter::filterDate($commentCreateDate, "Comment create date", true);
	}

	/**
	 * Accessor for whether or not comment is visible by student
	 *
	 * @return boolean
	 */
	public function getCommentStudentVisible() {
		return $this->commentStudentVisible;
	}

	/**
	 * Mutator for whether or not comment is visible by student
	 *
	 * @param boolean $commentStudentVisible
	 */
	public function setCommentStudentVisible($commentStudentVisible) {
		$this->commentStudentVisible = Filter::filterBoolean($commentStudentVisible, "Comment student visible");
	}

	/**
	 * Accessor for comment contents
	 *
	 * @return string
	 */
	public function getCommentText() {
		return $this->commentText;
	}

	/**
	 * Mutator for comment contents
	 *
	 * @param string $commentText
	 */
	public function setCommentText($commentText) {
		$this->commentText = Filter::filterString($commentText, "Comment text", 255);
	}

	/**
	 * Accessor for who submitted the comment
	 *
	 * @return string
	 */
	public function getCommentUsername() {
		return $this->commentUsername;
	}

	/**
	 * Mutator for who submitted the comment
	 *
	 * @param string $commentUsername
	 */
	public function setCommentUsername($commentUsername) {
		$this->commentUsername = Filter::filterString($commentUsername, "Comment username", 20);
	}

	/**
	 * Creates a json object out of this object
	 */
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["commentCreateDate"] = $this->commentCreateDate->format("U") * 1000;
		return ($fields);
	}

	/**
	 * Inserts comment into database
	 *
	 * @param PDO $pdo PDO connection
	 * @throws PDOException when MySQL related errors occur
	 */
	public function insert(PDO $pdo) {
		// Make sure this is a new comment
		if($this->commentId !== null) {
			throw(new PDOException("Not a new comment"));
		}

		// Create query template
		$query = "INSERT INTO comment(commentAlertId, commentCohortId, commentCreateDate, commentStudentId, commentStudentVisible, commentText, commentUsername) VALUES(:commentAlertId, :commentCohortId, :commentCreateDate, :commentStudentId, :commentStudentVisible, :commentText, :commentUsername)";
		$statement = $pdo->prepare($query);

		// Bind variables to template
		$formattedDate = $this->commentCreateDate->format("Y-m-d H:i:s");
		$parameters = array("commentAlertId" => $this->commentAlertId, "commentCohortId" => $this->commentCohortId, "commentCreateDate" => $formattedDate, "commentStudentId" => $this->commentStudentId, "commentStudentVisible" => $this->commentStudentVisible, "commentText" => $this->commentText, "commentUsername" => $this->commentUsername);
		$statement->execute($parameters);

		$this->setCommentId(intval($pdo->lastInsertId()));
	}

	/**
	 * Gets a comment by ID
	 *
	 * @param PDO $pdo PDO connection
	 * @param int $commentId comment ID to search by
	 * @return Comment|null null if nothing found, otherwise returns a comment
	 */
	public static function getCommentByCommentId(PDO $pdo, $commentId) {
		// Sanitize the ID before searching
		try {
			$commentId = Filter::filterInt($commentId, "Alert ID");
			// echo "ID: " . $commentId . PHP_EOL;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT commentId, commentAlertId, commentCohortId, commentCreateDate, commentStudentId, commentStudentVisible, commentText, commentUsername FROM comment WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);

		$parameters = array("commentId" => $commentId);
		$statement->execute($parameters);

		// Grab the comment from MySQL
		try {
			$comment = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false) {
				$comment = new Comment($row["commentId"], $row["commentAlertId"], $row["commentCohortId"], $row["commentStudentId"], $row["commentCreateDate"], $row["commentStudentVisible"], $row["commentText"], $row["commentUsername"]);
			}
		} catch(PDOException $pdoException) {
			throw new PDOException($pdoException->getMessage(), 0, $pdoException);
		}

		return $comment;
	}

	/**
	 * Gets all comments for a student
	 *
	 * @param PDO $pdo PDO connection
	 * @param int $commentStudentId student ID to search by
	 * @return SplFixedArray array of comments found
	 */
	public static function getCommentsByCommentStudentId(PDO $pdo, $commentStudentId) {
		// Sanitize the student ID
		try {
			$commentStudentId = Filter::filterString($commentStudentId, "Comment student ID");
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT commentId, commentAlertId, commentCohortId, commentCreateDate, commentStudentId, commentStudentVisible, commentText, commentUsername FROM comment WHERE commentStudentId = :commentStudentId";
		$statement = $pdo->prepare($query);

		// Bind parameters
		$parameters = array("commentStudentId" => $commentStudentId);
		$statement->execute($parameters);

		$comment = null;
		$comments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentAlertId"], $row["commentCohortId"], $row["commentStudentId"], $row["commentCreateDate"], $row["commentStudentVisible"], $row["commentText"], $row["commentUsername"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			} catch(Exception $exception) {
				throw new PDOException($exception->getMessage(), 0, $exception);
			}
		}

		return $comments;
	}

	/**
	 * Gets all comments by a user
	 *
	 * @param PDO $pdo PDO connection
	 * @param string $commentUsername username to search by
	 * @return SplFixedArray array of comments found
	 */
	public static function getCommentsByCommentUsername(PDO $pdo, $commentUsername) {
		// Sanitize the username
		try {
			$commentUsername = Filter::filterString($commentUsername, "Comment username", 20);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT commentId, commentAlertId, commentCohortId, commentCreateDate, commentStudentId, commentStudentVisible, commentText, commentUsername FROM comment WHERE commentUsername LIKE :commentUsername";
		$statement = $pdo->prepare($query);

		// Bind parameters
		$parameters = array("commentUsername" => $commentUsername);
		$statement->execute($parameters);

		$comment = null;
		$comments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentAlertId"], $row["commentCohortId"], $row["commentStudentId"], $row["commentCreateDate"], $row["commentStudentVisible"], $row["commentText"], $row["commentUsername"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			} catch(Exception $exception) {
				throw new PDOException($exception->getMessage(), 0, $exception);
			}
		}

		return $comments;
	}

	/**
	 * Gets all the comments for a cohort
	 *
	 * @param PDO $pdo PDO connection
	 * @param int $commentCohortId cohort ID to search by
	 * @return SplFixedArray array of comments found
	 */
	public static function getCommentsByCohortId(PDO $pdo, $commentCohortId) {
		// Sanitize the student ID
		try {
			$commentCohortId = Filter::filterString($commentCohortId, "Comment cohort ID");
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new PDOException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// Create query template
		$query = "SELECT commentId, commentAlertId, commentCohortId, commentCreateDate, commentStudentId, commentStudentVisible, commentText, commentUsername FROM comment WHERE commentCohortId = :commentCohortId";
		$statement = $pdo->prepare($query);

		// Bind parameters
		$parameters = array("commentCohortId" => $commentCohortId);
		$statement->execute($parameters);

		$comment = null;
		$comments = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentAlertId"], $row["commentCohortId"], $row["commentStudentId"], $row["commentCreateDate"], $row["commentStudentVisible"], $row["commentText"], $row["commentUsername"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			} catch(Exception $exception) {
				throw new PDOException($exception->getMessage(), 0, $exception);
			}
		}

		return $comments;
//		// Sanitize the cohort ID
//		try {
//			$cohortId = Filter::filterInt($cohortId, "Cohort ID");
//		} catch(InvalidArgumentException $invalidArgument) {
//			throw(new PDOException($invalidArgument->getMessage(), 0, $invalidArgument));
//		} catch(RangeException $range) {
//			throw(new PDOException($range->getMessage(), 0, $range));
//		} catch(Exception $exception) {
//			throw(new PDOException($exception->getMessage(), 0, $exception));
//		}
//
//		// Create query template
//		$query = "SELECT commentId, commentAlertId, commentCohortId, commentCreateDate, commentCohortId, commentStudentVisible, commentText, commentUsername FROM comment
//					 INNER JOIN student ON comment.commentCohortId = student.studentId
//					 WHERE studentCohortId = :cohortId
//					 ORDER BY studentName, commentCreateDate";
//		$statement = $pdo->prepare($query);
//
//		// Bind parameters
//		$parameters = array("cohortId" => $cohortId);
//		$statement->execute($parameters);
//
//		$comment = null;
//		$comments = new SplFixedArray($statement->rowCount());
//		$statement->setFetchMode(PDO::FETCH_ASSOC);
//		while(($row = $statement->fetch()) !== false) {
//			try {
//				$comment = new Comment($row["commentId"], $row["commentAlertId"], $row["commentCohortId"], $row["commentCohortId"], $row["commentCreateDate"], $row["commentStudentVisible"], $row["commentText"], $row["commentUsername"]);
//				$comments[$comments->key()] = $comment;
//				$comments->next();
//			} catch(Exception $exception) {
//				throw new PDOException($exception->getMessage(), 0, $exception);
//			}
//		}
//
//		return $comments;
	}

}