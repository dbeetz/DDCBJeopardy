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

	/**
	 * accessor method for gameQnaId
	 * @return int|null value of gameQnaId
	 **/
	public function getGameQnaId() {
		return($this->gameQnaId);
	}

	/**
	 * mutator method for gameQnaId
	 *
	 * @param int|null $newGameQnaId new value of gameQnaId
	 * @throws \RangeException if $newGameQnaId is not positive
	 * @throws \TypeError if $newGameQnaId is not an integer
	 **/
	public function setGameQnaId(int $newGameQnaId = null) {
		// base case: if the gameQnaId is null, this is a new gameQna id without a mySQL assigned id (yet)
		if($newGameQnaId === null) {
			$this->gameQnaId = null;
			return;
		}

		// verify the gameQnaId is positive
		if($newGameQnaId <= 0) {
			throw(new \RangeException("gameQnaId is not positive"));
		}

		// convert and store the gameQnaId
		$this->gameQnaId = $newGameQnaId;
	}

	/**
	 * accessor method for gameQnaGameId
	 * @return int value of gameQnaGameId; foreign key
	 **/
	public function getGameQnaGameId() {
		return($this->gameQnaGameId);
	}

	/**
	 * mutator method for gameQnaGameId
	 *
	 * @param int $newGameQnaGameId new value of gameQnaGameId
	 * @throws \RangeException if $newGameQnaGameId is not positive
	 * @throws \TypeError if $newGameQnaGameId is not an integer
	 **/
	public function setGameQnaGameId(int $newGameQnaGameId) {
		// verify the gameQnaGameId is positive
		if($newGameQnaGameId <= 0) {
			throw(new \RangeException("gameQnaGameId is not positive"));
		}

		// convert and store the gameQnaGameId
		$this->gameQnaGameId = $newGameQnaGameId;
	}

	/**
	 * accessor method for gameQnaQnaId
	 * @return int value of gameQnaQnaId; foreign key
	 **/
	public function getGameQnaQnaId() {
		return($this->gameQnaQnaId);
	}

	/**
	 * mutator method for gameQnaQnaId
	 *
	 * @param int $newGameQnaQnaId new value of gameQnaQnaId
	 * @throws \RangeException if $newGameQnaQnaId is not positive
	 * @throws \TypeError if $newGameQnaQnaId is not an integer
	 **/
	public function setGameQnaQnaId(int $newGameQnaQnaId) {
		// verify the gameQnaGameId is positive
		if($newGameQnaQnaId <= 0) {
			throw(new \RangeException("gameQnaQnaId is not positive"));
		}

		// convert and store the gameQnaGameId
		$this->gameQnaQnaId = $newGameQnaQnaId;
	}
}