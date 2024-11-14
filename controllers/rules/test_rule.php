<?php

namespace Controllers\Rules;

class TestRule {
	private $fields = [];
	private $name;

	function __construct($fields) {
		$this->fields = $fields;
	}

	/**
	 * Is string empty
	 *
	 * @param string $name The field name.
	 * @return boolean
	 */
	public function is_empty($name) {
		if (empty($this->fields[$name])) {
			return true;
		}
		return false;
	}

	/**
	 * Is set
	 *
	 * @param string $name The field name.
	 * @return boolean
	 */
	public function is_set($name) {
		if (isset($this->fields[$name])) {
			return true;
		}
		return false;
	}

	/**
	 * Is a name
	 *
	 * @param string $name The field name.
	 * @param string|null $regex_value Extra special characters for the regex to check against.
	 * @return boolean
	 */
	public function is_name($name, $regex_value = null) {
		if ($regex_value != null) {
			$regex_value = preg_quote($regex_value);
		}
		// Regex to match an upper and lowercase letters, spaces and other special characters that are passed to it.
		// Provided by https://stackoverflow.com/a/58964725/2358222
		$pattern = "/^(?!.*['-]{2})[a-zA-Z][a-zA-Z\s$regex_value]{1,}$/";
		if (preg_match($pattern, $this->fields[$name])) {
			return true;
		}
		return false;
	}

	/**
	 * Is a UK postcode format
	 *
	 * @param string $name The field name.
	 * @return boolean
	 */
	public function is_postcode($name) {
		// Regex to check UK postcode format.
		// Partially provided by https://stackoverflow.com/a/51885364/2358222 in
		// section 2. Simplified Patterns.
		$pattern = "/^[A-Za-z]{1,2}\d[A-Z\da-z]? ?\d[A-Za-z]{2}$/";

		if (preg_match($pattern, $this->fields[$name])) {
			return true;
		}
		return false;
	}

	/**
	 * Is an email format
	 *
	 * @param string $name The field name.
	 * @return boolean
	 */
	public function is_email($name) {
		if (filter_var($this->fields[$name], FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		return false;
	}

	/**
	 * Is a phone number (both UK mobile and landline)
	 *
	 * @param string $name The field name.
	 * @return boolean
	 */
	public function is_phone($name) {
		// Regex to check phone and mobile number.
		// Partially provided by https://stackoverflow.com/a/8099255/2358222
		$pattern = "/^(\+44\s?[1-9]\d{3}|\(?0[1-9]\d{3}\)?)\s?\d{3}\s?\d{3}$/";
		if (preg_match($pattern, $this->fields[$name])) {
			return true;
		}
		return false;
	}

	/**
	 * Is a UK vehicle registration number
	 *
	 * @param string $name The field name.
	 * @return boolean
	 */
	public function is_reg($name) {
		// Regex to check vehicle registration.
		// Provided by https://gist.github.com/danielrbradley/7567269
		
		// Reg: SH61 JJX
		$pattern = "/(^[A-Za-z]{2}[0-9]{2}\s?[A-Za-z]{3}$)|(^[A-Za-z][0-9]{1,3}[A-Za-z]{3}$)|(^[A-Za-z]{3}[0-9]{1,3}[A-Za-z]$)|(^[0-9]{1,4}[A-Za-z]{1,2}$)|(^[0-9]{1,3}[A-Za-z]{1,3}$)|(^[A-Za-z]{1,2}[0-9]{1,4}$)|(^[A-Za-z]{1,3}[0-9]{1,3}$)|(^[A-Za-z]{1,3}[0-9]{1,4}$)|(^[0-9]{3}[DX]{1}[0-9]{3}$)/";
		if (preg_match($pattern, $this->fields[$name])) {
			return true;
		}
		return false;
	}

	/**
	 * Is letters only
	 *
	 * @param string $name The field name.
	 * @return boolean
	 */
	public function is_letters($name) {
		if ($this->is_name($name, "-")) {
			return true;
		}
		return false;
	}

	/**
	 * Is character length shorter than a specified number
	 *
	 * @param string $name The field name.
	 * @param string $value The number.
	 * @return boolean
	 */
	public function is_shorter_than($name, $value) {
		if (strlen($this->fields[$name]) < $value) {
			return true;
		}
		return false;
	}
}
