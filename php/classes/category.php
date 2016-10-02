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
	 * this is the foreign key from Game
	 * @var int $categoryGameId
	**/
	private $categoryGameId;

	/**
	 * name of category
	 * @var string $categoryName
	**/
	private $categoryName;

	/**
	 * Constructor for this Category
	 *
	 * @param int|null $newCategoryId of this category or null if a new category
	 *@param int|null $newCategoryGameId id for the key from Game
	 *@param string $newCategoryName string containing category name
	 *@throws \InvalidArgumentException if data types are not valid
	 *@throws \RangeException if data values are out of bounds
	 *@throws \TypeError if data types violate type hints
	 *@throws \Exception if some other exception occurs
	**/

	public function __construct(int $newCategoryId = null, int $newCategoryGameId = null, string $newCategoryName) {
		try{
			$this->setCategoryId($newCategoryId);
			$this->setCategoryGameId($newCategoryGameId);
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
	 * Accessor method for categoryGameId
	 *
	 * @return int|null value of categoryGameId
	**/
	public function getCategoryGameId(){
		return($this->categoryGameId);
	}
}