<?php

require_once("autoloader.php");

/**
 * This class manages players in one game
 *
 * @author Eliot Ostling
 */
class player implements JsonSerializable {

private $playerId;

private $PlayergameId;

private $playerStudentId;

private $playerStudentCohortId;


public function __construct($playerId, $gameId, $studentId, $studentCohortId) {
  try {
    $this->setPlayerId($newPlayerId);
    $this->setPlayerGameId($newPlayerGameId);
    $this->setPlayerStudentId($newPlayerStudentId);
    $this->setPlayerStudentCohortId($newPlayerStudentCohortId);
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


/**
 * Accessor for Game ID
 *
 * @return int
 */
public function getPlayerGameId() {
  return $this->playerGameId;
}
//Mutator

public function setPlayerGameId(int $newPlayerGameId = null) {
  if($newPlayerGameId === null) {
    $this->playerGameId = null;
    return;
  }
  if($newPlayerGameId <= 0) {
    throw(new \RangeException("No"));
  }

  $this->playerGameId = $newPlayerGameId;
}


/**
 * Accessor for Player student ID
 *
 * @return int
 */
public function getPlayerStudentId() {
  return $this->playerStudentId;
}
//Mutator

public function setPlayerStudentId(int $newPlayerStudentId = null) {
  if($newPlayerStudentId === null) {
    $this->playerStudentId = null;
    return;
  }
  if($newPlayerStudentId <= 0) {
    throw(new \RangeException("No"));
  }

  $this->playerStudentId = $newPlayerStudentId;

}
