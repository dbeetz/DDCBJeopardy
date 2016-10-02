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
}