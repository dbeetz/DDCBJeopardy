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



}