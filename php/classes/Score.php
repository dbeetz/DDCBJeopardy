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
 */

class Score{
	/**
	 * scoreId this is the primary key
	 *
	 * @var int $scoreId
	 */
	private $scoreId;
	/**
	 * scoreGameId this is a foreign key
	 *
	 * @var int $scoreGameId
	 */
	private $scoreGameId;
	/**
	 * scoreStudentId this is a foreign key
	 *
	 * @var int $scoreStudentId
	 */
	private $scoreStudentId;
	/**
	 * @var int $scoreStudentScore
	 */
	private $scoreStudentScore;

	/**
	 * Score constructor
	 *
	 * @param int $newScoreId
	 * @param int $newScoreGameId
	 * @param int $newScoreStudentId
	 * @param int $newScoreStudentScore
	 * @throws \TypeError if variables are not the correct data type
	 * @throws \InvalidArgumentException for invalid exceptions
	 * @throws \RangeException for exceptions that are out of range
	 * @throws \Exception for all other exceptions
	 */

	public function __construct(int $newScoreId, int $newScoreGameId, int $newScoreStudentId, int $newScoreStudentScore) {
		try{
			$this->setScoreId($newScoreId);
			$this->setScoreGameId($newScoreGameId);
			$this->setScoreStudentId($newScoreStudentId);
			$this->setScoreStudentScore($newScoreStudentScore);
		}catch(\InvalidArgumentException $invalidArgument) {
			//rethrow exception
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			//rethrow exception
			throw(new \RangeException($range->getMessage(), 0, $range));
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
	public function getScoreId(){
		return ($this->scoreId);
	}

	/**
	 * Mutator for scoreId
	 *
	 * @param $newScoreId
	 * @throws \TypeError if variables are not the correct data type
	 * @throws \RangeException if scoreId is not valid
	 */
	public function setScoreId(int $newScoreId){
		//verify scoreId is valid
		if($newScoreId === null){
			$this->scoreId = null;
			return;
		}
		if($newScoreId <=0){
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
	/**
	 * @return int
	 */
	public function getScoreGameId(){
		return ($this->scoreGameId);
	}
	/**
	 * Mutator method for scoreGameId
	 *
	 * @param $newScoreGameId
	 * @throws \TypeError if variables are not the correct data type
	 * @throws \RangeException if scoreGameId is not valid
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
	public function getScoreStudentId(){
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
	public function getScoreStudentScore(){
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
}