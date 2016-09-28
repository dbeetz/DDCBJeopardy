<?php

namespace Edu\Cnm\Jeopardy\Test;

//use Edu\Cnm\Jeopardy\{GameQna, Game, Qna};

// grab the project test parameters
require_once("JeopardyTest.php");

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/php/classes/autoloader.php");

/**
 * Full PHPUnit test for the GameQna class
 *
 * This is a complete PHPUnit test of the GameQna class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs
 *
 * @see GameQna
 * @author Zac Laudick <zlaudick@cnm.edu>
 **/
class GameQnaTest extends JeopardyTest {
	/**
	 * game that uses the GameQna
	 * @var Game game
	 **/
	protected $game = null;

	/**
	 * Qna class that stores the GameQna
	 * @var Qna qna
	 **/
	protected $qna = null;

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp() {
		// run the default setUp method first
		parent::setUp();

		// create and insert a Game
		$this->game = new Game(null);
		$this->game->insert($this->getPDO());
	}
}