<?php
namespace Edu\Cnm\DDCBJeopardy;
require_once ("autoloader.php");

/**
 * @author Christina Sosa <csosa4@cnm.edu>
 *
 **/

class Category implements \JsonSerializable{

	/**
	 * Id for category; this is the primary key
	 * @var int $categoryId
	**/
	private $categoryId;

	/**
	 * name of category
	 * @var string $categoryName
	**/
	private $categoryName;

	/**
	 * Constructor for this Category
	 *
	 * @param int|null $newCategoryId of this category or null if a new category
	 *@param string $newCategoryName string containing category name
	 *@throws \InvalidArgumentException if data types are not valid
	 *@throws \RangeException if data values are out of bounds
	 *@throws \TypeError if data types violate type hints
	 *@throws \Exception if some other exception occurs
	**/

	public function __construct(int $newCategoryId = null, string $newCategoryName) {
		try{
			$this->setCategoryId($newCategoryId);
			$this->setCategoryName($newCategoryName);
		} catch(\InvalidArgumentException $invalidArgument){
			//rethrow exception to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			//rethrow exception to caller
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			//rethrow the exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		}catch(\Exception $exception) {
			//rethrow exception to the caller
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * Accessor method for categoryId
	 *
	 * @return int|null value of categoryId
	**/
	public function getCategoryId(){
		return($this->categoryId);
	}

	/**
	 * Mutator method for categoryId
	 *
	 * @param int $newCategoryId new value of category Id
	 * @throws \RangeException if $newCateogoryId is not positive
	 * @throws \TypeError if $newCategoryId is not an integer
	**/

	public function setCategoryId(int $newCategoryId = null){
		if($newCategoryId === null){
			$this->categoryId = null;
			return;
		}
		//verify the category id is positive
		if($newCategoryId <= 0){
			throw (new \RangeException("Category ID is not positive"));
		}
		//convert and store the category id
		$this->categoryId = $newCategoryId;
	}

	/**
	 * accessor method for category name
	 *
	 * @return string value of category name
	 */
	public function getCategoryName() {
		return($this->categoryName);
	}

	/**
	 * mutator method for category name
	 *
	 * @param string $newCategoryName new value of category name
	 * @throws \InvalidArgumentException if $newCategoryName is not a string or insecure
	 * @throws \RangeException if $newCategoryName is > 128 characters
	 * @throws \TypeError if $newCategoryName is not a string
	 */
	public function setCategoryName(string $newCategoryName) {
		// verify the category name is secure
		$newCategoryName = trim($newCategoryName);
		$newCategoryName = filter_var($newCategoryName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newCategoryName) === true) {
			throw(new \InvalidArgumentException("category name content is empty or insecure"));
		}
		// verify the category name will fit in the database
		if(strlen($newCategoryName) > 128) {
			throw(new \RangeException("category name is too long"));
		}
		// store the category name
		$this->categoryName = $newCategoryName;
	}

	/**
	 * inserts category information into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function insert(\PDO $pdo) {
		// enforce the categoryId is null
		if($this->categoryId !== null) {
			throw(new \PDOException("not a new category"));
		}
		// create query template
		$query = "INSERT INTO category(categoryName) VALUES(:categoryName)";

		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = ["categoryName" => $this->categoryName];
		$statement->execute($parameters);
		// update the null categoryId with what mySQL just gave us
		$this->categoryId = intval($pdo->lastInsertId());
	}

	/**
	 * updates the category data in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function update(\PDO $pdo) {
		// enforce the categoryId is not null (don't update the category data that hasn't been inserted yet
		if($this->categoryId === null) {
			throw(new \PDOException("unable to update the category data that doesn't exist"));
		}
		// create query template
		$query = "UPDATE category SET categoryName = :categoryName";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = [ "categoryName" => $this->categoryName, "categoryId" => $this->categoryId];
		$statement->execute($parameters);
	}
	/**
	 * deletes this category from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function delete(\PDO $pdo) {
		// enforce the categoryId is not null (don't delete a category that has just been inserted)
		if($this->categoryId === null) {
			throw(new \PDOException("unable to delete a category that does not exist"));
		}
		// create query template
		$query = "DELETE FROM category WHERE categoryId = :categoryId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holder in the template
		$parameters = ["categoryId" => $this->categoryId];
		$statement->execute($parameters);
	}


	/**
	 * gets the category name by content
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $categoryName category name content to search for
	 * @return \SplFixedArray SplFixedArray of category data found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 */

	public static function getCategoryByCategoryName(\PDO $pdo, string $categoryName) {
		// sanitize the description before searching
		$categoryName = trim($categoryName);
		$categoryName = filter_var($categoryName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($categoryName) === true) {
			throw(new \PDOException("category name is invalid"));
		}
		// create query template
		$query = "SELECT categoryId, categoryName  FROM category WHERE categoryName LIKE :categoryName";
		$statement = $pdo->prepare($query);
		// bind the category content to the place holder in the template
		$categoryName = "%$categoryName%";
		$parameters = array("categoryName" => $categoryName);
		$statement->execute($parameters);
		// build an array of categories
		$categories = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$category = new Category($row["categoryId"], $row["categoryName"]);
				$categories[$categories->key()] = $category;
				$categories->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($categories);
	}

	/**
	 * get the category by categoryId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $categoryId category id to search for
	 * @return Category|null category found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 */

	public static function getCategoryByCategoryId(\PDO $pdo, int $categoryId) {
		// sanitize the categoryId before searching
		if($categoryId <= 0) {
			throw(new \PDOException("category id is not positive"));
		}
		// create query template
		$query = "SELECT categoryId,categoryName FROM category WHERE categoryId = :categoryId";
		$statement = $pdo->prepare($query);
		// bind the category id to the place holder in the template
		$parameters = array("categoryId" => $categoryId);
		$statement->execute($parameters);
		// grab the category from mySQL
		try {
			$category = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$category = new Category($row["categoryId"], $row["categoryName"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($category);
	}

	/**
	 * gets all Categories
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Categories found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllCategories(\PDO $pdo) {
		// create query template
		$query = "SELECT categoryId, categoryName FROM category";
		$statement = $pdo->prepare($query);
		$statement->execute();
		// build an array of categories
		$categories = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$category = new Category($row["categoryId"], $row["categoryName"]);
				$categories[$categories->key()] = $category;
				$categories->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($categories);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 */
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		return($fields);
	}
}