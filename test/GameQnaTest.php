<?php

namespace Edu\Cnm\DDCBJeopardy\Test;

use Edu\Cnm\DDCBJeopardy\{Game, Qna, Category};

// grab the project test parameters
require_once("DDCBJeopardyTest.php");

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
class GameQnaTest extends DDCBJeopardyTest {
	/**
	 * game that uses the GameQna
	 * @var Game game
	 **/
	protected $game = null;

	/**
	 * Qna that uses the GameQna
	 * @var Qna qna
	 **/
	protected $qna = null;

	/**
	 * Category for the qna
	 * @var Category category
	 **/
	protected $category = null;

	/**
	 * timestamp of the Message; starts as null and is assigned later
	 * @var \DateTime $VALID_MESSAGEDATE
	 **/
	protected $dateTime = null;

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp() {
		// run the default setUp method first
		parent::setUp();

		//calculate the date using the time the unit test was set up
		$this->dateTime = new \DateTime();

		// create and insert a game
		$this->game = new Game(null, 1, $this->dateTime, 1);

		// create and insert a category
		$this->category = new Category(null, "css");

		// create and insert a qna
		$this->qna = new Qna(null, $this->category->getCategoryId(), "Answer", 16, "Question");
	}

	/**
	 * test inserting a valid Message and verifying that actual MySQL data matches
	 **/
	public function testInsertValidGameQna() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("gameQna");

		// create a new gameQna and insert into mySQL
		$gameQna = new GameQna(null, $this->game->getGameId(), $this->qna->getQnaId());

		//grab the data from MySQL and enforce that the fields match our expectations
		$pdoGameQna = GameQna::getGameQnaByGameQnaId($this->getPDO(), $gameQna->getGameQnaId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("gameQna"));
		$this->assertEquals($pdoGameQna->getGameQnaGameId(), $this->game->getGameId());
	}

	/**
	 * test inserting a gameQna that already exists
	 * @expectedException \PDOException
	 **/
	public function testInsertInvalidGameQna() {
		// create a gameQna with a non null id and watch it fail
		$gameQna = new GameQna(DDCBJeopardyTest::INVALID_KEY, $this->game->getGameId(), $this->qna->getQnaId());
		$gameQna->insert($this->getPDO());
	}
}