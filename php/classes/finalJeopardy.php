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
}