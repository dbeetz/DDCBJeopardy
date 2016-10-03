<?php
namespace Edu\Cnm\Jeopardy\Test;

//use Edu\Cnm\Jeopardy\{};

//grab the project parameters
require_once ("JeopardyTest.php");
//grab the class under scrutiny
require_once (dirname(__DIR__) . "/public_html/php/classes/autoload.php");

/**
 * PHPUnit test for Score class
 *
 * @see ScoreTest
 * @author Robert Engelbert <rob@robertengelbert.com>
 */
class ScoreTest extends JeopardyTest{
	/**
	 * @var int $scoreGameId
	 */
	protected $gameId;
	/**
	 * @var int $scoreStudentId
	 */
	protected $studentId;
	/**
	 * @var int $scoreStudentScore
	 */
	protected $studentScore;
}