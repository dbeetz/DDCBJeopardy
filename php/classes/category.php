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

	public function __construct() {
	}
}