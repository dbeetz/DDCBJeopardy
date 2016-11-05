<?php

require_once("autoloader.php");

/**
 * This class manages players in one game
 *
 * @author Eliot Ostling
 */
class player implements JsonSerializable {

private $playerId;

private $gameId;

private $studentId;

private $studentCohortId;


public function __construct($playerId, $gameId, $studentId, $studentCohortId) {
  try {
    $this->setPlayerId($newPlayerId);
    $this->setGameId($newGameId);
    $this->setStudentId($newStudentId);
    $this->setStudentCohortId($newStudentCohortId);
  } catch(RangeException $range) {
    throw new RangeException($range->getMessage(), 0, $range);
  } catch(InvalidArgumentException $invalidArgument) {
    throw new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument);
  } catch(Exception $exception) {
    throw new Exception($exception->getMessage(), 0, $exception);
  }
}

/**
 * Accessor for player ID
 *
 * @return int
 */
public function getPlayerId() {
  return $this->playerId;
}
//Mutator

public function setPlayerId(int $newPlayerId = null) {
  if($newPlayerId === null) {
    $this->playerId = null;
    return;
  }
  if($newPlayerId <= 0) {
    throw(new \RangeException("No"));
  }

  $this->playerId = $newPlayerId;
}
