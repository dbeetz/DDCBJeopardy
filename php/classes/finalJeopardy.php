<?php
namespace Edu\Cnm\DDCBJeopardy;

require_once ("autoloader.php");

/**
 * Class for Final Jeopardy round of the game - queue the music
 *
 * @author Giles Sandoval <gsandoval2277@gmail.com>
 * @version 1.0.0
 */

class FinalJeopardy implements \JsonSerializable {
	/**
	 * Id to identify the final jeopardy round. This is primary key
	 * @var int $finalJeopardyId
	 */
	private $finalJeopardyId;

	/**
	 * Id identify the final jeopardy & game. This is foreign key from the game class
	 * @var int $finalJeopardyGameId
	 **/
	private $finalJeopardyGameId;

	/**
	 * Id identify the final jeopardy & qna entity. This is foreign key from the qna class
	 * @var int $finalJeopardyQnaId
	 **/
	private $finalJeopardyQnaId;

	/**
	 * Id identify the final jeopardy & student entity. This is foreign key from the student class
	 * @var int $finalJeopardyStudentId
	 **/
	private $finalJeopardyStudentId;

	/**
	 * Answer for the final jeopardy round
	 * @var string $finalJeopardyAnswer
	 **/
	private $finalJeopardyAnswer;

	/**
	 * Wager for the final jeopardy round
	 * @var int $finalJeopardyWager
	 **/
	private $finalJeopardyWager;


	/**
	 * constructor for final jeopardy
	 * @param int|null $newFinalJeopardyId of the finalJeopardy round or null if new final jeopardy round
	 * @param int|null $newFinalJeopardyGameId of the final jeopardy round or null if new game in the final jeopardy round
	 * @param int|null $newFinalJeopardyQnaId of the final jeopardy round or null if new qna in the final jeopardy round
	 * @param int|null $newFinalJeopardyStudentId of the final jeopardy round or null if new student in the final jeopardy round
	 * @param string $newFinalJeopardyAnswer string containing answer for the final round
	 * @param int|null $newFinalJeopardyWager int wager for the final round
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
}