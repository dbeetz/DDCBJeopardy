<?php

/**
 * @author Christina Sosa <csosa2106@gmail.com>
 */
namespace Edu\Cnm\DDCBJeopardy\Test;
use Edu\Cnm\DDCBJeopardy\Category;
// grab the project test parameters
require_once("DDCBJeopardyTest.php");
// grab the class under scrutiny
require_once ("../php/classes/autoloader.php");
/**
 * Unit testing for the Category class for DDCBJeopardy
 *
 * @see Category
 **/
class CategoryTest extends DDCBJeopardy {
	/**
	 * content of the Category
	 * @var string $VALID_CATEGORYNAME
	 **/
	protected $VALID_CATEGORYNAME = "CSS";
	/**
	 * content of the updated Category
	 * @var string $VALID_CATEGORYNAME2
	 **/
	protected $VALID_CATEGORYNAME2 = "Object Oriented PHP";
	/**
	 * test inserting a valid Category and verify that the actual mySQL data matches
	 **/
	public function testInsertValidCategory() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("category");
		// create a new category and insert into mySQL
		$category = new Category(null, $this->VALID_CATEGORYNAME);
		$category->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoCategory = Category::getCategoryByCategoryId($this->getPDO(), $category->getCategoryId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("category"));
		$this->assertEquals($pdoCategory->getCategoryName(), $this->VALID_CATEGORYNAME);
	}
	/**
	 * test inserting a Category that already exists
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertInvalidCategory() {
		// create a Category with a non null category id and watch it fail
		$category = new Category(CartridgeCodersTest::INVALID_KEY, $this->VALID_CATEGORYNAME);
		$category->insert($this->getPDO());
	}
	/**
	 * test inserting a Category, editing it, and then updating it
	 **/
	public function testUpdateValidCategory() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("category");
		// create a new Category and insert into mySQL
		$category = new Category(null, $this->VALID_CATEGORYNAME);
		$category->insert($this->getPDO());
		// edit the Category and update it in mySQL
		$category->setCategoryName($this->VALID_CATEGORYNAME2);
		$category->update($this->getPDO());
		// grab the data from mySQL and enforce that the fields match our expectations
		$pdoCategory = Category::getCategoryByCategoryId($this->getPDO(), $category->getCategoryId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("category"));
		$this->assertEquals($pdoCategory->getCategoryName(), $this->VALID_CATEGORYNAME2);
	}
	/**
	 * test updating a Category that already exists
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidCategory() {
		// create a Category with a non null category id and watch it fail
		$category = new Category(null, $this->VALID_CATEGORYNAME);
		$category->update($this->getPDO());
	}
	/**
	 * test creating a Category and then deleting it
	 **/
	public function testDeleteValidCategory() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("category");
		// create a new Category and insert into mySQL
		$category = new Category(null, $this->VALID_CATEGORYNAME);
		$category->insert($this->getPDO());
		// delete the Category from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("category"));
		$category->delete($this->getPDO());
		// grab the data from mySQL and enforce that the Category does not exist
		$pdoCategory = Category::getCategoryByCategoryId($this->getPDO(), $category->getCategoryId());
		$this->assertNull($pdoCategory);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("category"));
	}
	/**
	 * test deleting a Category that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidCategory() {
		// create a Category and try to delete it without actually inserting it
		$category = new Category(null, $this->VALID_CATEGORYNAME);
		$category->delete($this->getPDO());
	}
	/**
	 * test grabbing a Category that does not exist
	 **/
	public function testGetInvalidCategoryByCategoryId() {
		// grab a category id that exceeds the maximum allowable category id
		$category = Category::getCategoryByCategoryId($this->getPDO(), CartridgeCodersTest::INVALID_KEY);
		$this->assertNull($category);
	}
	/**
	 * test grabbing a Category by category name
	 **/
	public function testGetValidCategoryByCategoryName() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("category");
		// create a new Category and insert it into mySQL
		$category = new Category(null, $this->VALID_CATEGORYNAME);
		$category->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Category::getCategoryByCategoryName($this->getPDO(), $category->getCategoryName());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("category"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\CartridgeCoders\\Category", $results);
		// grab the result from the array and validate it
		$pdoCategory = $results[0];
		$this->assertEquals($pdoCategory->getCategoryName(), $this->VALID_CATEGORYNAME);
	}
	/**
	 * test grabbing a Category by name that does not exist
	 **/
	public function testGetInvalidCategoryByCategoryName() {
		// grab a category id that exceeds the maximum allowable category id
		$category = Category::getCategoryByCategoryName($this->getPDO(), "this category name never existed");
		$this->assertCount(0, $category);
	}
	/**
	 * test grabbing all Categories
	 **/
	public function testGetAllValidCategories() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("category");
		// create a new Category and insert to into mySQL
		$category = new Category(null, $this->VALID_CATEGORYNAME);
		$category->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Category::getAllCategories($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("category"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\CartridgeCoders\\Category", $results);
		// grab the result from the array and validate it
		$pdoCategory = $results[0];
		$this->assertEquals($pdoCategory->getCategoryName(), $this->VALID_CATEGORYNAME);
	}
}
