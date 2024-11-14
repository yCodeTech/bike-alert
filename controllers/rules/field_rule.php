<?php

namespace Controllers\Rules;

/**
 * Inspired by https://github.com/ChrisWojcik/simple-form-validator/tree/master
 */
class FieldRule {
	private $field_name;
	private $rule_name;
	private $rule_value;

	public function __construct($field_name, $rule_name, $rule_value = null) {
		$this->field_name = $field_name;
		$this->rule_name = $rule_name;
		$this->rule_value = $rule_value;
	}

	/**
	 * A special PHP Magic method to allow a private property
	 * be accessed outside of this class.
	 *
	 * @param string $prop The property name.
	 */
	public function __get($prop) {
		if (isset($this->$prop)) {
			return $this->$prop;
		}
	}
}
