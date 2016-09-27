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
	}
}