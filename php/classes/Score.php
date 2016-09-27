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
}