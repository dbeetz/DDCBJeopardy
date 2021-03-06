<?php

/**
 * This class contains methods to filter variables
 *
 * @author Skyler Rexroad
 */
class Filter {

	/**
	 * Filters an integer
	 *
	 * @param int $int integer to use
	 * @param string $name name of attribute to filter
	 * @param boolean $nullable whether the int can be null or not
	 * @return int|null new ID to use
	 */
	public static function filterInt($int, $name, $nullable = false) {
		// Base case: If the ID is null, this is a new object without a MySQL assigned ID
		if($nullable === true && $int === null) {
			return (null);
		}

		// Make sure the int is not null
		if($int === null) {
			throw(new InvalidArgumentException("$name cannot be null"));
		}

		// Verify the new int
		$int = filter_var($int, FILTER_VALIDATE_INT);
		if($int === false) {
			throw(new InvalidArgumentException("$name not a valid integer"));
		}

		// Verify the new int is positive
		if($int < 0) {
			throw(new RangeException("$name not positive"));
		}

		// Convert and return the new int
		return (intval($int));
	}

	/**
	 * Filters a double
	 *
	 * @param double $double double to use
	 * @param string $name name of attribute to filter
	 * @return double|null new ID to use
	 */
	public static function filterDouble($double, $name) {
		// Make sure the int is not null
		if($double === null) {
			throw(new InvalidArgumentException("$name cannot be null"));
		}

		// Verify the new int
		$double = filter_var($double, FILTER_VALIDATE_FLOAT);
		if($double === false) {
			throw(new InvalidArgumentException("$name not a valid double"));
		}

		// Convert and return the new int
		return (doubleval($double));
	}

	/**
	 * Filters a string
	 *
	 * @param string $string string to use
	 * @param string $name name of attribute to filter
	 * @param int $size maximum length of the string
	 * @param mixed $flags additional flags to pass to filter_var()
	 * @return mixed new string to use
	 */
	public static function filterString($string, $name, $size = 0, $flags = FILTER_FLAG_NO_ENCODE_QUOTES) {
		// Verify the new string
		$string = trim($string);
		$string = filter_var($string, FILTER_SANITIZE_STRING, $flags);
		if(empty($string) === true) {
			throw(new InvalidArgumentException("$name is invalid"));
		}

		// Verify that the string will fit in the database
		if($size > 0 && strlen($string) > $size) {
			throw(new RangeException("$name is too long"));
		}

		// Return the new string
		return ($string);
	}

	/**
	 * Filters an email
	 *
	 * @param string $email string to use
	 * @param string $name name of attribute to filter
	 * @param int $size maximum length of the string
	 * @return mixed new string to use
	 */
	public static function filterEmail($email, $name, $size = 0) {
		// Verify the new email
		$email = trim($email);
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		if(empty($email) === true) {
			throw(new InvalidArgumentException("$name is invalid"));
		}

		// Verify that the email will fit in the database
		if($size > 0 && strlen($email) > $size) {
			throw(new RangeException("$name is too long"));
		}

		// Return the new email
		return ($email);
	}

	public static function filterBoolean($boolean, $name) {
		// Verify the boolean
		$$boolean = filter_var($boolean, FILTER_VALIDATE_BOOLEAN);
		if($boolean === null) {
			throw new InvalidArgumentException("$name is invalid");
		}

		// Return the boolean
		return $boolean;
	}

	/**
	 * Validates a date.
	 *
	 * Stolen mercilessly from Dylan McDonald.
	 *
	 * @param DateTime $date
	 * @param string $name
	 * @param bool|false $now
	 * @return DateTime
	 */
	public static function filterDate($date, $name, $now = false) {
		// Base case: if the date is a DateTime object, there's no work to be done
		if(is_object($date) === true && get_class($date) === "DateTime") {
			return ($date);
		}

		// Second base case: if $now is true, create a timestamp and return it
		if($date === null && $now === true) {
			return new DateTime();
		}

		// Treat the date as a mySQL date string: Y-m-d H:i:s
		$date = trim($date);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $date, $matches)) !== 1) {
			throw(new InvalidArgumentException("$name is not a valid date"));
		}

		// Verify the date is really a valid calendar date
		$year = intval($matches[1]);
		$month = intval($matches[2]);
		$day = intval($matches[3]);
		$hour = intval($matches[4]);
		$minute = intval($matches[5]);
		$second = intval($matches[6]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("$name is not a Gregorian date"));
		}

		// Verify the time is really a valid wall clock time
		if($hour < 0 || $hour >= 24 || $minute < 0 || $minute >= 60 || $second < 0 || $second >= 60) {
			throw(new RangeException("$name is not a valid time"));
		}

		// If we got here, the date is clean
		$date = DateTime::createFromFormat("Y-m-d H:i:s", $date, new DateTimeZone("UTC"));
		return ($date);
	}

}