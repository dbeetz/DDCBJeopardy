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
	 * test inserting a valid GameQna and verifying that actual MySQL data matches
	 **/
	public function testInsertValidGameQna() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("gameQna");

		// create a new gameQna and insert into mySQL
		$gameQna = new GameQna(null, $this->game->getGameId(), $this->qna->getQnaId());

		// grab the data from MySQL and enforce that the fields match our expectations
		$pdoGameQna = GameQna::getGameQnaByGameQnaId($this->getPDO(), $gameQna->getGameQnaId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("gameQna"));
		$this->assertEquals($pdoGameQna->getGameQnaGameId(), $this->game->getGameId());
	}

	/**
	 * test inserting a GameQna that already exists
	 * @expectedException \PDOException
	 **/
	public function testInsertInvalidGameQna() {
		// create a gameQna with a non null id and watch it fail
		$gameQna = new GameQna(DDCBJeopardyTest::INVALID_KEY, $this->game->getGameId(), $this->qna->getQnaId());
		$gameQna->insert($this->getPDO());
	}

	/**
	 * test inserting a valid GameQna and then deleting it
	 **/
	public function testDeleteValidGameQna() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("gameQna");

		// create a new gameQna and insert into mySQL
		$gameQna = new GameQna(null, $this->game->getGameId(), $this->qna->getQnaId());

		//delete the message from MySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("gameQna"));
		$gameQna->delete($this->getPDO());

		// grab the data from MySQL and enforce the gameQna does not exist
		$pdoGameQna = GameQna::getGameQnaByGameQnaId($this->getPDO(), $gameQna->getGameQnaId());
		$this->assertNull($pdoGameQna);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("gameQna"));
	}

	/**
	 * test deleting a GameQna that does not exist
	 *
	 * @expectedException \PDOException
	 **/
	public function testDeleteInvalidGameQna() {
		// create a GameQna and try to delete it without inserting it
		$gameQna = new GameQna(null, $this->game->getGameId(), $this->qna->getQnaId());
		$gameQna->delete($this->getPDO());
	}

	/**
	 * test grabbing a GameQna by gameQnaGameId
	 **/
	public function testGetGameQnaByGameQnaGameId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("gameQna");

		// create a new gameQna and insert into mySQL
		$gameQna = new GameQna(null, $this->game->getGameId(), $this->qna->getQnaId());

		// grab the data from MySQL and enforce that the fields match our expectations
		$results = GameQna::getGameQnaByGameQnaGameId($this->getPDO(), $gameQna->getGameQnaGameId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("gameQna"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DDCBJeopardy\\GameQna", $results);

		// grab the result from the array and validate it
		$pdoGameQna = $results[0];
		$this->assertEquals($pdoGameQna->getGameQnaGameId(), $this->game->getGameId());
		$this->assertEquals($pdoGameQna->getGameQnaQnaId(), $this->qna->getQnaId());
	}

	/**
	 * test grabbing a GameQna by a gameQnaGameId that does not exist
	 **/
	public function testGetInvalidGameQnaByGameQnaGameId() {
		// grab a message by a gameQnaGameId that does not exist
		$gameQna = GameQna::getGameQnaByGameQnaGameId($this->getPDO(), DDCBJeopardyTest::INVALID_KEY);
		$this->assertCount(0, $gameQna);
	}

	/**
	 * test grabbing a GameQna by gameQnaQnaId
	 **/
	public function testGetGameQnaByGameQnaQnaId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("gameQna");

		// create a new gameQna and insert into mySQL
		$gameQna = new GameQna(null, $this->game->getGameId(), $this->qna->getQnaId());

		// grab the data from MySQL and enforce that the fields match our expectations
		$results = GameQna::getGameQnaByGameQnaQnaId($this->getPDO(), $gameQna->getGameQnaQnaId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("gameQna"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DDCBJeopardy\\GameQna", $results);

		// grab the result from the array and validate it
		$pdoGameQna = $results[0];
		$this->assertEquals($pdoGameQna->getGameQnaGameId(), $this->game->getGameId());
		$this->assertEquals($pdoGameQna->getGameQnaQnaId(), $this->qna->getQnaId());
	}

	/**
	 * test grabbing a GameQna by a gameQnaQnaId that does not exist
	 **/
	public function testGetInvalidGameQnaByGameQnaQnaId() {
		// grab a message by a gameQnaGameId that does not exist
		$gameQna = GameQna::getGameQnaByGameQnaQnaId($this->getPDO(), DDCBJeopardyTest::INVALID_KEY);
		$this->assertCount(0, $gameQna);
	}

	/**
	 * test grabbing all gameQna's
	 **/
	public function testGetAllGameQnas() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("gameQna");

		// create a new gameQna and insert into mySQL
		$gameQna = new GameQna(null, $this->game->getGameId(), $this->qna->getQnaId());

		// grab the data from MySQL and enforce that the fields match our expectations
		$results = GameQna::getAllGameQnas($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("gameQna"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DDCBJeopardy\\GameQna", $results);

		// grab the result from the array and validate it
		$pdoGameQna = $results[0];
		$this->assertEquals($pdoGameQna->getGameQnaGameId(), $this->game->getGameId());
		$this->assertEquals($pdoGameQna->getGameQnaQnaId(), $this->qna->getQnaId());
	}
}