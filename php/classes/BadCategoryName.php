<?php

namespace edu\cnm\DDCBJeopardy;
require_once("autoload.php");

/**
 * class BadCategoryName for entity badCategoryName in the jeopardy application
 *this class contains all state variables, constructor, setters, getters, PDOs, and getFooByBar methods
 * @author Loren Baca
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
		$newBadCategoryNameName = filter_input($newBadCategoryNameName, FILTER_SANITIZE_STRING);

		/*check if $newBadCategoryNameName is either empty or too long */
		if(strlen($newBadCategoryNameName) === 0) {
			throw(new \RangeException("Bad category name is empty"));
		}

		if(strlen($newBadCategoryNameName) > 128) {
			throw(new \RangeException("Bad category name is too long!"));
		}

		/*everything checks out, assign badCategoryNameName to $newBadCategoryNameName */
		$this->badCategoryNameName = $newBadCategoryNameName;
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

		$parameters = ["badCategoryNameCategoryId"=>$this->badCategoryNameCategoryId, "badCategoryNameGameId"=>$this->badCategoryNameGameId,];

		$statement->execute($parameters);

	}



}








