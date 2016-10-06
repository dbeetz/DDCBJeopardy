<?php
namespace Edu\Cnm\DDCBJeopardy;

require_once("autoloader.php");

/**
 * GameQna class for Jeopardy
 *
 * @author Zac Laudick <zaclaudick@gmail.com>
 **/
class GameQna implements \JsonSerializable {
	/**
	 * id for this GameQna; this is the primary key
	 * @var int $gameQnaId
	 **/
	private $gameQnaId;

	/**
	 * id for the game using the gameQna; this is a foreign key
	 * @var int $gameQnaGameId
	 **/
	private $gameQnaGameId;

	/**
	 * id for the qna used in the gameQna; this is a foreign key
	 * @var int $gameQnaQnaId
	 **/
	private $gameQnaQnaId;

	/**
	 * constructor for this GameQna
	 *
	 * @param int|null $newGameQnaId id of this GameQna or null if new GameQna
	 * @param int $newGameQnaGameId foreign key from Game
	 * @param int $newGameQnaQnaId foreign key from Qna
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct(int $newGameQnaId = null, int $newGameQnaGameId, int $newGameQnaQnaId) {
		try {
			$this->setGameQnaId($newGameQnaId);
			$this->setGameQnaGameId($newGameQnaGameId);
			$this->setGameQnaQnaId($newGameQnaQnaId);
		} catch(\InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			// rethrows the exception to the caller
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			// rethrow the exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception) {
			// rethrow the exception to the caller
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}
}