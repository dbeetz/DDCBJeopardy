<?php

namespace edu\cnm\DDCBJeopardy;
require_once("autoload.php");

/**
 * class BadCategoryName for entity badCategoryName in the jeopardy application
 *this class contains all state variables, constructor, setters, getters, PDOs, and getFooByBar methods
 * @author Loren Baca baca.loren@gmail.com
 */
class BadCategoryName implements \JsonSerializable {

	/**
	 * foreign key for badCategoryName class is badCategoryNameCategoryId linking to Category Class
	 * @var badCategoryNameCategoryId
	 */
	private $badCategoryNameCategoryId;


	/**
	 * foreign key for badCategoryName class is badCategoryNameGameId linking to Game Class
	 * @var badCategoryNameGameId
	 */
	private $badCategoryNameGameId;


	/**
	 * name assigned to badCategoryNameName
	 * @var badCategoryNameName
	 */
	private $badCategoryNameName;


	/*----------------------------GETTERs  and SETTERs for badCategoryName Class-------------------*/


	/*--------SETTER & GETTER for badCategoryNameCategoryId------------------*/
	/**
	 * getter method for badCategoryNameCategoryId
	 * @return int $badCategoryNameCategoryId
	 **/
	public function getBadCategoryNameCategoryId() {
		return ($this->badCategoryNameCategoryId);
	}

	/**
	 * setter method for badCategoryNameCategoryId
	 * @param int $newBadCategoryNameCategoryId new value of badCategoryNameCategoryId
	 * @throws \InvalidArgumentException if $newBadCategoryNameCategoryId is null
	 * @throws \RangeException if $newBadCategoryNameCategoryId is not positive
	 * @throws \TypeError if $newBadCategoryNameCategoryId is not an integer
	 * @throws \Exception if any other exception occurs
	 **/
	public function setBadCategoryNameCategoryId(int $newBadCategoryNameCategoryId) {
		//verify that the profile id is positive
		if($newBadCategoryNameCategoryId === null) {
			throw(new \InvalidArgumentException("badCategoryNameCategoryId cannot be null"));
		}
		if($newBadCategoryNameCategoryId <= 0) {
			throw(new \RangeException("badCategoryNameCategoryId is not positive"));
		}
		//convert and store the badCategoryNameCategoryId
		$this->badCategoryNameCategoryId = $newBadCategoryNameCategoryId;
	}



	/*--------SETTER & GETTER for badCategoryNameGameId------------------*/
	/**
	 * getter method for badCategoryNameGameId
	 * @return int $badCategoryNameGameId
	 **/
	public function getBadCategoryNameGameId() {
		return ($this->badCategoryNameGameId);
	}

	/**
	 * setter method for badCategoryNameGameId
	 * @param int $newBadCategoryNameGameId new value of badCategoryNameGameId
	 * @throws \InvalidArgumentException if $newBadCategoryNameGameId is null
	 * @throws \RangeException if $newBadCategoryNameGameId is not positive
	 * @throws \TypeError if $newBadCategoryNameGameId is not an integer
	 * @throws \Exception if any other exception occurs
	 **/
	public function setBadCategoryNameGameId(int $newBadCategoryNameGameId) {
		//verify that the profile id is positive
		if($newBadCategoryNameGameId === null) {
			throw(new \InvalidArgumentException("badCategoryNameGameId cannot be null"));
		}
		if($newBadCategoryNameGameId <= 0) {
			throw(new \RangeException("badCategoryNameGameId is not positive"));
		}
		//convert and store the badCategoryNameGameId
		$this->badCategoryNameGameId = $newBadCategoryNameGameId;
	}



	/*--------SETTER & GETTER for badCategoryNameName------------------*/
	/**
	 * getter method for badCategoryNameName
	 * @return string value for $badCategoryNameName
	 **/
	public function getBadCategoryNameName() {
		return ($this->badCategoryNameName);
	}

	/**
	 * setter method for badCategoryNameName
	 * @param string $newBadCategoryNameName new value of badCategoryNameName
	 * @throws \InvalidArgumentException if $newBadCategoryNameName is null
	 * @throws \RangeException if $newBadCategoryNameName is empty or too long
	 * @throws \TypeError if $newBadCategoryNameName is not a string
	 * @throws \Exception if any other exception occurs
	 **/
	public function setBadCategoryNameName(string $newBadCategoryNameName) {
		/*strip out all white space, and sanitize*/
		$newBadCategoryNameName = trim($newBadCategoryNameName);
		$newBadCategoryNameName = filter_var($newBadCategoryNameName, FILTER_SANITIZE_STRING);

		/*check if $newBadCategoryNameName is either empty or too long */
		if(strlen($newBadCategoryNameName) === 0) {
			throw(new \RangeException("Bad category name is empty"));
		}

		if(strlen($newBadCategoryNameName) > 128) {
			throw(new \RangeException("Bad category name is too long!"));
		}

		$nameCheck = "SELECT badCategoryNameCategoryId AND badCategoryNameGameId FROM badCategoryName WHERE badCategoryNameName = :newBadCategoryNameName";

		if(mysql_num_rows($nameCheck) === 0) {
			/*everything checks out, assign badCategoryNameName to $newBadCategoryNameName */
			$this->badCategoryNameName = $newBadCategoryNameName;
		} else {
			throw(new \InvalidArgumentException("You have already used that bad category name Dylan, be more clever!"));
		}


	}


	/*-------------PDO/SQL Methods-----------------------*/

	/**
	 * inserts this badCategoryName into mySQL
	 *
	 * @param \PDO $pdo is the PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 *
	 **/

	public function insert(\PDO $pdo) {

		if($this->badCategoryNameCategoryId === null || $this->badCategoryNameGameId === null) {
			throw(new \InvalidArgumentException("The foreign key cannot be null!"));
		}
		if($this->badCategoryNameName === null) {
			throw(new \InvalidArgumentException("The 'Bad Category Name' cannot be null!"));
		}

		/*----Create query template-----*/
		$query = "INSERT INTO badCategoryName(badCategoryNameCategoryId, badCategoryNameGameId, badCategoryNameName) VALUES(:badCategoryNameCategoryId, :badCategoryNameGameId, :badCategoryNameName)";

		$statement = $pdo->prepare($query);

		$parameters = ["badCategoryNameCategoryId" => $this->badCategoryNameCategoryId, "badCategoryNameGameId" => $this->badCategoryNameGameId,];

		$statement->execute($parameters);
	}


	/**
	 * deletes this badCategoryName from mySQL
	 *
	 * @param \PDO $pdo is the PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 *
	 **/

	public function delete(\PDO $pdo) {

		if($this->badCategoryNameCategoryId === null || $this->badCategoryNameGameId === null) {
			throw(new \InvalidArgumentException("Cannot delete a name that doesnt exist!"));
		}
		if($this->badCategoryNameName === null) {
			throw(new \InvalidArgumentException("The 'Bad Category Name' does not exist!"));
		}

		/*----Create query template-----*/
		$query = "DELETE FROM badCategoryName WHERE badCategoryNameCategoryId = :badCategoryNameCategoryId AND badCategoryNameGameId = :badCategoryNameGameId";

		$statement = $pdo->prepare($query);

		$parameters = ["badCategoryNameCategoryId" => $this->badCategoryNameCategoryId, "badCategoryNameGameId" => $this->badCategoryNameGameId,];

		$statement->execute($parameters);

	}


	/*-------------Get Foo by Bar Section----------------------*/

	/**
	 * get badCategoryName by badCategoryNameCategoryId
	 * @param \PDO $pdo PDO connection object
	 * @param int $badCategoryNameCategoryId is the composite key to search for
	 * @return \SplFixedArray SplFixedArray of badCategoryName's found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public function getBadCategoryNameByBadCategoryNameCategoryId(\PDO $pdo, int $badCategoryNameCategoryId) {
//		Sanitize input, check for bad values
		if($badCategoryNameCategoryId <= 0) {
			throw(new \PDOException("badCategoryNameCategoryId cannot be negative or 0"));
		}

		$query = "SELECT badCategoryNameCategoryId, badCategoryNameGameId, badCategoryNameName FROM badCategoryName WHERE badCategoryNameCategoryId = :badCategoryNameCategoryId";

		$statement = $pdo->prepare($query);

		$parameters = ["badCategoryNameCategoryId" => $badCategoryNameCategoryId];

		$statement->execute($parameters);

		//build an array of badCategoryNames...
		$badCategoryNames = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$badCategoryName = new BadCategoryName($row["badCategoryNameCategoryId"], $row["badCategoryNameGameId"], $row["badCategoryNameName"]);
				$badCategoryNames[$badCategoryNames->key()] = $badCategoryName;
				$badCategoryNames->next();
			} catch(\Exception $exception) {
				//if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($badCategoryNames);
	}


	/**
	 * get badCategoryName by badCategoryNameGameId
	 * @param \PDO $pdo PDO connection object
	 * @param int $badCategoryNameGameId is the composite key to search for
	 * @return \SplFixedArray SplFixedArray of badCategoryName's found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public function getBadCategoryNameByBadCategoryNameGameId(\PDO $pdo, int $badCategoryNameGameId) {
//		Sanitize input, check for bad values
		if($badCategoryNameGameId <= 0) {
			throw(new \PDOException("badCategoryNameGameId cannot be negative or 0"));
		}

		$query = "SELECT badCategoryNameCategoryId, badCategoryNameGameId, badCategoryNameName FROM badCategoryName WHERE badCategoryNameGameId = :badCategoryNameGameId";

		$statement = $pdo->prepare($query);

		$parameters = ["badCategoryNameGameId" => $badCategoryNameGameId];

		$statement->execute($parameters);

		//build an array of badCategoryNames...
		$badCategoryNames = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$badCategoryName = new BadCategoryName($row["badCategoryNameCategoryId"], $row["badCategoryNameGameId"], $row["badCategoryNameName"]);
				$badCategoryNames[$badCategoryNames->key()] = $badCategoryName;
				$badCategoryNames->next();
			} catch(\Exception $exception) {
				//if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($badCategoryNames);
	}


	/**
	 * get badCategoryName by badCategoryNameName
	 * @param \PDO $pdo PDO connection object
	 * @param string $badCategoryNameName is the composite key to search for
	 * @return BadCategoryName|null for object found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public function getBadCategoryNameByBadCategoryNameName(\PDO $pdo, string $badCategoryNameName) {
//		Sanitize input, check for bad values
		$badCategoryNameName = trim($badCategoryNameName);
		$badCategoryNameName = filter_var($badCategoryNameName, FILTER_SANITIZE_STRING);


//		DO WE WANT TO FIND AN EXACT MATCH TO THE NAME OR AN ARRAY OF MATCHES??
		if(empty($badCategoryNameName) === true) {
			throw(new \InvalidArgumentException("Must enter a name to search for!"));
		}

		if(strlen($badCategoryNameName) > 128) {
			throw(new \RangeException("The name entered cannot be longer than 128 characters"));
		}

		$query = "SELECT badCategoryNameCategoryId, badCategoryNameGameId, badCategoryNameName FROM badCategoryName WHERE badCategoryNameName = :badCategoryNameName";

		$statement = $pdo->prepare($query);

		$parameters = ["badCategoryNameName" => $badCategoryNameName];

		$statement->execute($parameters);

//		Now that we have selected the correct profile, we need to grab it from SQL
		try {
			$badCategoryName = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false){
				$badCategoryName = new BadCategoryName($row["badCategoryNameCategoryId"], $row["badCategoryNameGameId"], $row["badCategoryNameName"]);
			}

		}catch(\Exception $exception){
//			if row couldnt be converted, throw PDO $exception
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		return $badCategoryName;
	}


	/**
	 * get badCategoryName by badCategoryNameCategoryId and badCategoryNameGameId
	 * @param \PDO $pdo PDO connection object
	 * @param int $badCategoryNameCategoryId badCategoryNameCategoryId to search for
	 * @param int $badCategoryNameGameId badCategoryNameGameId to search for
	 * @return badCategoryName|null employ if found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/

	public function getBadCategoryNameByBadCategoryNameCategoryIdAndBadCategoryNameGameId(\PDO $pdo, int $badCategoryNameCategoryId, int $badCategoryNameGameId){

		if($badCategoryNameCategoryId === null || $badCategoryNameGameId === null){
			throw(new \PDOException("The input foreign keys cannot be null!"));
		}

		if($badCategoryNameCategoryId <= 0 || $badCategoryNameGameId <= 0){
			throw(new \PDOException("The input foreign keys cannot be 0 or negative!"));
		}

		$query = "SELECT badCategoryNameCategoryId, badCategoryNameGameId, badCategoryNameName FROM badCategoryName WHERE badCategoryNameCategoryId = :badCategoryNameCategoryId AND badCategoryNameGameId = :badCategoryNameGameId";

		$statement = $pdo->prepare($query);

		$parameters = ["badCategoryNameCategoryId" => $badCategoryNameCategoryId, "badCategoryNameGameId" => $badCategoryNameGameId];

		$statement->execute($parameters);
	}

		//added JsonSerialize
		/**
		 * formats the state variables for JSON serialization
		 *
		 * @return array resulting state variables to serialize
		 */
		public function jsonSerialize() {
			$fields = get_object_vars($this);
			return($fields);
		}
}
