<?php
namespace Edu\Cnm\DDCBJeopardy;

require_once("autoload.php");

/**
 * Welcome to the QNA Class. We're so glad you're here!
 *
 * @author Monica Alvarez <mmalvar13@gmail.com>
 *
 */
class Qna implements \JsonSerializable {
	/**
	 * id for this QNA; this is the primary key
	 * @var int $qnaId
	 **/
	private $qnaId;
	/**
	 * id of the category this QNA is under; this is a foregin key
	 * @var int $qnaCategoryId
	 **/
	private $qnaCategoryId;
	/**
	 * answer for this QNA
	 * @var string $qnaAnswer
	 **/
	private $qnaAnswer;
	/**
	 * point value for this QNA
	 * @var int $qnaPointVal
	 **/
	private $qnaPointVal;
	/**
	 * question for this QNA
	 * @var string $qnaQuestions
	 **/
	private $qnaQuestion;


	/*-------------------------------------------Constructor-------------------------------------------------------*/

	/**
	 * QNA constructor
	 * @param int|null $newQnaId id of this QNA or null if new QNA
	 * @param int $newQnaCategoryId id of the Category this QNA is in
	 * @param string $newQnaAnswer answer for this QNA
	 * @param int $newQnaPointVal number of points this QNA is worth
	 * @param string $newQnaQuestion question for this QNA
	 * @throws \InvalidArgumentException
	 * @throws \RangeException
	 * @throws \TypeError
	 * @throws \Exception
	 **/
	public function __construct(int $newQnaId = null, int $newQnaCategoryId, string $newQnaAnswer, int $newQnaPointVal, string $newQnaQuestion) {
		try {
			$this->setQnaId($newQnaId);
			$this->setQnaCategoryId($newQnaCategoryId);
			$this->setQnaAnswer($newQnaAnswer);
			$this->setQnaPointVal($newQnaPointVal);
			$this->setQnaQuestion($newQnaQuestion);
		} catch(\InvalidArgumentException $invalidArgument) {
			//rethrow the exception to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			//rethrow the exception to the caller
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			//rethrow the exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception) {
			//rethrow the exception to the caller
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}

	/*-----------------------------------------Accessor Methods------------------------------------------*/

	/**
	 * accessor method for qnaId
	 *
	 * @return int|null value of qnaId
	 **/
	public function getQnaId() {
		return ($this->qnaId);
	}

	/**
	 * accessor method for qnaCategoryId
	 *
	 * @return int value of qnaCategoryId
	 **/
	public function getQnaCategoryId() {
		return ($this->qnaCategoryId);
	}

	/**
	 * accessor method for qnaAnswer
	 *
	 * @return string value for qnaAnswer
	 **/
	public function getQnaAnswer() {
		return ($this->qnaAnswer);
	}

	/**
	 * accessor method for qnaPointVal
	 *
	 * @return int value of qnaPointVal
	 **/
	public function getQnaPointVal() {
		return ($this->qnaPointVal);
	}

	/**
	 * accessor method for qnaQuestion
	 *
	 * @return string value for qnaQuestion
	 **/
	public function getQnaQuestion() {
		return ($this->qnaQuestion);
	}







}