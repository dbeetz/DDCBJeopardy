<?php
namespace Edu\Cnm\DDCBJeopardy;

require_once("autoload.php");

/**
 * Welcome to the QNA Class. We're so glad you're here!
 *
 * @author Monica Alvarez <mmalvar13@gmail.com>
 *
 */
class Qna implements \JsonSerializable {
	/**
	 * id for this QNA; this is the primary key
	 * @var int $qnaId
	 **/
	private $qnaId;
	/**
	 * id of the category this QNA is under; this is a foregin key
	 * @var int $qnaCategoryId
	 **/
	private $qnaCategoryId;
	/**
	 * answer for this QNA
	 * @var string $qnaAnswer
	 **/
	private $qnaAnswer;
	/**
	 * point value for this QNA
	 * @var int $qnaPointVal
	 **/
	private $qnaPointVal;
	/**
	 * question for this QNA
	 * @var string $qnaQuestions
	 **/
	private $qnaQuestion;


	/*-------------------------------------------Constructor-------------------------------------------------------*/

	/**
	 * QNA constructor
	 * @param int|null $newQnaId id of this QNA or null if new QNA
	 * @param int $newQnaCategoryId id of the Category this QNA is in
	 * @param string $newQnaAnswer answer for this QNA
	 * @param int $newQnaPointVal number of points this QNA is worth
	 * @param string $newQnaQuestion question for this QNA
	 * @throws \InvalidArgumentException
	 * @throws \RangeException
	 * @throws \TypeError
	 * @throws \Exception
	 **/
	public function __construct(int $newQnaId = null, int $newQnaCategoryId, string $newQnaAnswer, int $newQnaPointVal, string $newQnaQuestion) {
		try {
			$this->setQnaId($newQnaId);
			$this->setQnaCategoryId($newQnaCategoryId);
			$this->setQnaAnswer($newQnaAnswer);
			$this->setQnaPointVal($newQnaPointVal);
			$this->setQnaQuestion($newQnaQuestion);
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

	/*-----------------------------------------Accessor Methods------------------------------------------*/

	/**
	 * accessor method for qnaId
	 *
	 * @return int|null value of qnaId
	 **/
	public function getQnaId() {
		return ($this->qnaId);
	}

	/**
	 * accessor method for qnaCategoryId
	 *
	 * @return int value of qnaCategoryId
	 **/
	public function getQnaCategoryId() {
		return ($this->qnaCategoryId);
	}

	/**
	 * accessor method for qnaAnswer
	 *
	 * @return string value for qnaAnswer
	 **/
	public function getQnaAnswer() {
		return ($this->qnaAnswer);
	}

	/**
	 * accessor method for qnaPointVal
	 *
	 * @return int value of qnaPointVal
	 **/
	public function getQnaPointVal() {
		return ($this->qnaPointVal);
	}

	/**
	 * accessor method for qnaQuestion
	 *
	 * @return string value for qnaQuestion
	 **/
	public function getQnaQuestion() {
		return ($this->qnaQuestion);
	}


	/*--------------------------------------------Mutator Methods--------------------------------------------------*/

	/**
	 * mutator method for qnaId
	 * @param int|null $newQnaId new value of qnaId
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if $newQnaId is not positive
	 * @throws \TypeError if$newQnaId is not an integer
	 **/
	public function setQnaId(int $newQnaId = null) {
		//base case: if the qnaId is null, this is a new qna without a mySQL assigned id yet
		if($newQnaId === null) {
			$this->qnaId = null;
			return;
		}
		//verify that the qnaId is positive
		if($newQnaId <= 0) {
			throw(new \RangeException("qna id is not positive"));
		}

		//convert and store the qna id
		$this->qnaId = $newQnaId;
	}


	/**
	 * mutator method for qnaCategoryId
	 * @param int $newQnaCategoryId new value of qnaCategoryId
	 * @throws \RangeException if $newQnaCategoryId is not positive
	 * @throws \TypeError if $newQnaCategoryId is not an integer
	 **/
	public function setQnaCategoryId(int $newQnaCategoryId) {
		//verify that the $newQnaCategoryId is positive
		if($newQnaCategoryId <= 0) {
			throw(new \RangeException("the qna category id is not positive"));
		}

		//convert and store the qna category id
		$this->qnaCategoryId = $newQnaCategoryId;
	}


	/**
	 * mutator method for qnaAnswer
	 * @param string $newQnaAnswer new value of qnaAnswer
	 * @throws \RangeException if $newQnaAnswer is empty or too long
	 * @throws \InvalidArgumentException if $newQnaAnswer is not a string
	 * @throws \TypeError if $newQnaAnswer is not a string
	 **/
	public function setQnaAnswer(string $newQnaAnswer) {
		//strip out white space on either end of $newQnaAnswer
		$newQnaAnswer = trim($newQnaAnswer);
		//sanitize $newQnaAnswer
		$newQnaAnswer = filter_var($newQnaAnswer, FILTER_SANITIZE_STRING);
		//check if $newQnaAnswer is empty
		if(strlen($newQnaAnswer) === 0) {
			throw(new \RangeException("Must enter Qna Answer"));
		}
		//check if $newQnaAnswer is too long
		if(strlen($newQnaAnswer) > 256) {
			throw(new \RangeException("Qna Answer is too long"));
		}
		//convert and store the qna answer
		$this->qnaAnswer = $newQnaAnswer;
	}


	/**
	 * mutator method for qnaPointVal
	 * @param int $newQnaPointVal is the new value of qnaPointVal
	 * @throws \RangeException if $newQnaPointVal is empty or too long
	 * @throws \TypeError if $newQnaPointVal is not an int
	 **/

	public function setQnaPointVal(int $newQnaPointVal) {
		//strip out white space on either end of qnaPointVal
		$newQnaPointVal = trim($newQnaPointVal);

		//sanitize $newQnaPointVal
		$newQnaPointVal = filter_var($newQnaPointVal, FILTER_SANITIZE_NUMBER_INT); //idk if i do this here, or how i would do this

		//verify if $newQnaPointVal is positive
		if($newQnaPointVal <= 0) {
			throw(new \RangeException("The qnaPointVal must be positive"));
		}

		//check if $newQnaPointVal is too long
		if($newQnaPointVal > 32) {
			throw(new \RangeException("The qnaPointVal cannot be greater than 32"));
		}

		//convert and store the qnaPointVal
		$this->qnaPointVal = $newQnaPointVal;


	}


	/**
	 * mutator method for qnaQuestion
	 * @param string $newQnaQuestion is the new value of qnaQuestion
	 * @throws \RangeException if $newQnaQuestion is empty or too long
	 * @throws \TypeError if $newQnaQuestion is not a string
	 **/
	public function setQnaQuestion(string $newQnaQuestion) {
		//strip out white space on either end of qnaQuestion
		$newQnaQuestion = trim($newQnaQuestion);

		//sanitize $newQnaQuestion
		$newQnaQuestion = filter_var($newQnaQuestion, FILTER_SANITIZE_STRING);

		//check if $newQnaQuestion is empty
		if(strlen($newQnaQuestion) === 0) {
			throw(new \RangeException("Must enter a QNA question"));
		}

		//check if $newQnaQuestion is too long
		if(strlen($newQnaQuestion) > 256) {
			throw(new \RangeException("qnaQuestion is too long"));
		}

		//convert and store the qnaQuestion
		$this->qnaQuestion = $newQnaQuestion;
	}


	/*---------------------------------------INSERT---------------------------*/
	/**
	 * Inserts this QNA into mySQL
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		//enforce the qnaId is null(i.e. don't insert a QNA that already exists)
		if($this->qnaId !== null) {
			throw(new \PDOException("not a new QNA"));
		}
		//create query template
		$query = "INSERT INTO qna(qnaCategoryId, qnaAnswer, qnaPointVal, qnaQuestion) VALUES(:qnaCategoryId, :qnaAnswer, :qnaPointVal, :qnaQuestion)";

		$statement = $pdo->prepare($query);

		//bind the member variables to the placeholders in the template
		$parameters = ["qnaCategoryId" => $this->qnaCategoryId, "qnaAnswer" => $this->qnaAnswer, "qnaPointVal" => $this->qnaPointVal, "qnaQuestion" => $this->qnaQuestion];

		$statement->execute($parameters);

		//update the null qnaId with what mySQL just gave us
		$this->qnaId = intval($pdo->lastInsertId());


	}

	/*---------------------------DELETE-----------------------------*/
	/**
	 * deletes this QNA from mySQL
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		//enforce the qnaID is not null (i.e. don't delete a tweet that hasn't been inserted)
		if($this->qnaId === null) {
			throw(new \PDOException("unable to delete a QNA that does not exist"));
		}

		//create a query template
		$query = "DELETE FROM qna WHERE qnaId = :qnaId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holder in the template
		$parameters = ["qnaId" => $this->qnaId];
		$statement->execute($parameters);
	}



	/*-------------------------------------UPDATE---------------------*/
	/**
	 * updates this QNA in mySQL
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException if mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) {
		//enforce the qnaId is not null(i.e. don't update an event that hasn't been inserted
		if($this->qnaId === null) {
			throw(new \PDOException("unable to update a QNA that does not exist"));
		}
		//create a query template
		$query = "UPDATE qna SET qnaCategoryId = :qnaCategoryId, qnaAnswer = :qnaAnswer, qnaPointVal = :qnaPointVal, qnaQuestion = :qnaQuestion WHERE qnaId = :qnaId";

		$statement = $pdo->prepare($query);

		//bind the member variables to the place holders in the template

		$parameters = ["qnaCategoryId" => $this->qnaCategoryId, "qnaAnswer" => $this->qnaAnswer, "qnaPointVal" => $this->qnaPointVal, "qnaQuestion" => $this->qnaQuestion, "qnaId" => $this->qnaId];
		$statement->execute($parameters);

	}



	/*----------------------------------------------Get Foo By Bars--------------------------------------------*/


	/**
	 * gets the QNA by qnaId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $qnaId qnaId to search for
	 * @return qna|null qna found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getQnaByQnaId(\PDO $pdo, int $qnaId){
		//sanitize the qnaId before searching by checking that it is a positive numbere
		if($qnaId <= 0){
			throw(new \PDOException("qnaId is not positive"));
		}
		//create query template
		$query = "SELECT qnaId, qnaCategoryId, qnaAnswer, qnaPointVal, qnaQuestion FROM qna WHERE qnaId = :qnaId";
		$statement = $pdo->prepare($query);

		//bind the qna id to the placeholder in the template
		$parameters = ["qnaId" => $qnaId];
		$statement->execute($parameters);

		//grab the even from mySQL
		try{
			$qna = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false){
				$qna = new Qna($row["qnaId"], $row["qnaCategoryId"], $row["qnaAnswer"], $row["qnaPointVal"], $row["qnaQuestion"]);
			}
		}catch(\Exception $exception){
			//if the row couldn't be converted rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($qna);
	}


	/**
	 * get the qna by qnaCategoryId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $qnaCategoryId qna category id to search by
	 * @return \SplFixedArray SplFixedArray of QNAs found
	 * @throws \PDOException when mysql related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 * @throws \RangeException when out of range
	 **/
	public static function getQnaByQnaCategoryId(\PDO $pdo, int $qnaCategoryId){
		//sanitize the category id before searching by making sure it's positive
		if($qnaCategoryId <= 0){
			throw(new \RangeException("qna category id must be positive"));
		}
		//create query template
		$query = "SELECT qnaId, qnaCategoryId, qnaAnswer, qnaPointVal, qnaQuestion FROM qna WHERE qnaCategoryId = :qnaCategoryId";
		$statement = $pdo->prepare($query);

		//bind the qna category id to the placeholder in the template
		$parameters = ["qnaCategoryId"=>$qnaCategoryId];
		$statement->execute($parameters);

		//build an array of QNAs
		$qnas = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false){
			try{
				$qna = new Qna($row["qnaId"], $row["qnaCategoryId"], $row["qnaAnswer"], $row["qnaPointVal"], $row["qnaQuestion"]);
				$qnas[$qnas->key()] = $qna;
				$qnas->next();
			}catch(\Exception $exception){
				//if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($qnas);
	}

	/**
	 * get QNA by qnaPointVal
	 * @param \PDO $pdo PDO connection object
	 * @param int $qnaPointVal qna point value to search by
	 * @return \SplFixedArray SplFixedArray of QNAs found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 * @throws \RangeException when out of range
	 **/
	public static function getQnaByQnaPointVal(\PDO $pdo, int $qnaPointVal){
		//sanitize the qnaPointVal before searching by making sure it's positive
		if($qnaPointVal <= 0){
			throw(new \RangeException("qna point value must be positive"));
		}
		//create query template
		$query = "SELECT qnaId, qnaCategoryId, qnaAnswer, qnaPointVal, qnaQuestion FROM qna WHERE qnaPointVal = :qnaPointVal";
		$statement = $pdo->prepare($query);

		//bind the qna poiint value to the placeholder in the template
		$parameters = ["qnaPointVal" => $qnaPointVal];
		$statement->execute($parameters);

		//build an array of QNAs
		$qnas = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false){
			try{
				$qna = new Qna($row["qnaId"], $row["qnaCategoryId"], $row["qnaAnswer"], $row["qnaPointVal"], $row["qnaQuestion"]);
				$qnas[$qnas->key()] = $qna;
				$qnas->next();
			}catch(\Exception $exception){
				//if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($qnas);
	}


	/**
	 * gets all QNA's
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of QNAs found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public function getAllQnas(\PDO $pdo){
		//create query template
		$query = "SELECT qnaId, qnaCategoryId, qnaAnswer, qnaPointVal, qnaQuestion FROM Qna";

		$statement = $pdo->prepare($query);
		$statement->execute();

		//build an array of Qnas
		$qnas = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false){
			try{
				$qna = new Qna($row["qnaId"], $row["qnaCategoryId"], $row["qnaAnswer"], $row["qnaPointVal"], $row["qnaQuestion"]);
				$qnas[$qnas->key()] = $qna;
				$qnas->next();
			}catch(\Exception $exception){
				//if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($qnas);
	}

	/**
	 * Formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		return ($fields);
		// TODO: Implement jsonSerialize() method. do i need to add anything here?
	}


}