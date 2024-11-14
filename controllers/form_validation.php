<?php

namespace Controllers;

use Controllers\Rules\FieldRule;
use Controllers\Rules\TestRule;

/**
 * Inspired by https://github.com/ChrisWojcik/simple-form-validator/tree/master
 */
class FormValidation extends BaseController {
	private $fields;
	private $file;
	public $errors = [];
	private $rules = [];
	private $test_rule;
	private $report_ref;

	function __construct() {
		$this->fields = $_POST['report'] ?? "";
		$this->file = new ProcessFile($_FILES["footage_upload"]);
		$this->test_rule = new TestRule($this->fields);
		$this->sanitise_values();

		// var_dump($this->fields);
		
		$this->add_rule("firstname", ["required", "name"], ["name" => "'-"]);
		$this->add_rule("lastname", ["required", "name"], ["name" => "'-"]);
		$this->add_rule("address_line_1", "required");
		$this->add_rule("town_or_city", ["required", "name"], ["name" => "'.-"]);
		$this->add_rule("county", ["required", "name"]);
		$this->add_rule("postcode", ["required", "postcode"]);
		$this->add_rule("email", ["required", "email"]);
		$this->add_rule("mobile", ["required", "phone"]);
		$this->add_rule("phone", "phone");
		$this->add_rule("date_of_birth", "required");
		$this->add_rule("preferred_contact_method", "required");
		$this->add_rule("incident_location", ["required"]);
		$this->add_rule("incident_county", ["required", "name"], ["name" => "'.-"]);
		$this->add_rule("date_time", "required");
		$this->add_rule("vehicle_make", "letters");
		$this->add_rule("vehicle_model", "letters");
		$this->add_rule("vehicle_registration", ["required", "reg"]);
		$this->add_rule("vehicle_colour", "letters");
		$this->add_rule("vehicle_location_in_relation", ["required", "minlength"], ["minlength" => "20"]);
		$this->add_rule("incident_description", ["required", "minlength"], ["minlength" => "20"]);
		$this->add_rule("footage_upload", "footage");
		$this->add_rule("camera_fitting", "required");
		$this->add_rule("footage_datetime_difference_reason", ["requires", "minlength"], ["requires" => "footage_datetime_difference_switch", "minlength" => "10"]);
		$this->add_rule("attend_court_confirmation", "is_set");
		$this->add_rule("statement_retention_confirmation", "is_set");
		$this->add_rule("casualty_prevention_confirmation", "is_set");
		$this->add_rule("statement_true_confirmation", "is_set");
	}

	/**
	 * Validate the fields
	 */
	public function validate() {
		if (!empty($this->fields)) {
			$this->errors = [];

			foreach ($this->rules as $rule) {
				$this->test_rule($rule);
			}
		}

		if ($this->has_errors()) {
			$array = [
				"errors" => $this->errors,
				"postback_value" => $this->fields
			];

			parent::load_report($array);
		}
		else {
			return ["fields" => $this->fields, "file" => $this->file->get_file()];
		}
	}

	/**
	 * Add a new rule for a field.
	 *
	 * @param string $field_name The name of the field
	 * @param string|array $rule_name The name or names of the rule(s) for the field to be validated against
	 * @param mixed $rule_value [Optional] Extra value for the rule to check against.
	 */
	private function add_rule($field_name, $rule_name, $rule_value = null) {
		
		// If rulename is an array...
		if (gettype($rule_name) === "array") {
			// Loop through the rulename array
			foreach ($rule_name as $name) {
				$value = "";
				
				// If rulevalue is an array...
				if (gettype($rule_value) === "array") {
					// If rulename key exists in the rulevalue array...
					if (array_key_exists($name, $rule_value)) {
						// Set rulevalue to the correct rulevalue for the rulename key.
						$value = $rule_value[$name];
					}
				}
				
				$this->rules[] = new FieldRule($field_name, $name, $value);
			}
		}
		else {
			$this->rules[] = new FieldRule($field_name, $rule_name, $rule_value);
		}

		// var_dump($this->rules);
	}
	
	/**
	 * Test the field value against the rule
	 *
	 * @param FieldRule $rule The rule object
	 */
	private function test_rule($rule) {

		if (isset($this->errors[$rule->field_name])) {
			return;
		}

		/**
		 * Required Rules
		 */

		// Normal required
		if ($rule->rule_name === "required") {
			if ($this->test_rule->is_empty($rule->field_name)) {
				$this->set_error($rule->field_name, "is $rule->rule_name");
			}
		}
		// Is it set? - checkboxes
		if ($rule->rule_name === "is_set") {
			if (!$this->test_rule->is_set($rule->field_name)) {
				$this->set_error($rule->field_name, "is required");
			}
		}
		// The field requires another field
		if ($rule->rule_name === "requires") {
			if ($this->test_rule->is_set($rule->rule_value) && $this->test_rule->is_empty($rule->field_name)) {
				$this->set_error($rule->field_name, "is required");
			}
		}
		// The footage upload
		if ($rule->rule_name === "footage") {
			$this->file->validate();

			if ($this->file->has_error()) {
				$error = $this->file->get_error();
				$this->set_error($rule->field_name, $error);
			}
		}
		
		// Make sure the field value isn't an empty string.
		if (!empty($this->fields[$rule->field_name])) {
			/**
			 * Specific Field Validation Rules
			 */

			// Name
			if ($rule->rule_name === "name") {
				if (!$this->test_rule->is_name($rule->field_name, $rule->rule_value)) {
					$msg = "";
					if ($rule->rule_value != null) {
						$msg = ", and the following special characters: <b>$rule->rule_value</b>";
					}

					$this->set_error($rule->field_name, "must only contain letters, spaces$msg");
				}
			}
			// Postcode
			if ($rule->rule_name === "postcode") {
				if (!$this->test_rule->is_postcode($rule->field_name)) {
					$this->set_error($rule->field_name, "doesn't seem to be valid");
				}
			}
			// Email
			if ($rule->rule_name === "email") {
				if (!$this->test_rule->is_email($rule->field_name)) {
					$this->set_error($rule->field_name, "doesn't seem to be valid, must follow the format name@provider.extension");
				}
			}
			// Phone
			if ($rule->rule_name === "phone") {
				if (!$this->test_rule->is_phone($rule->field_name)) {
					$this->set_error($rule->field_name, "doesn't seem to be valid");
				}
			}
			// Vehicle Registration
			if ($rule->rule_name === "reg") {
				if (!$this->test_rule->is_reg($rule->field_name)) {
					$this->set_error($rule->field_name, "doesn't seem to be valid");
				}
			}
			// Letters only
			if ($rule->rule_name === "letters") {
				if (!$this->test_rule->is_letters($rule->field_name) && !$this->test_rule->is_empty($rule->field_name)) {
					$this->set_error($rule->field_name, "must only contain letters");
				}
			}
			// Min Character Length
			if ($rule->rule_name === "minlength") {
				if ($this->test_rule->is_shorter_than($rule->field_name, $rule->rule_value)) {
					$this->set_error($rule->field_name, "must be longer than $rule->rule_value characters");
				}
			}
		}
	}

	/**
	 * Is there any errors?
	 *
	 * @return boolean
	 */
	public function has_errors() {
		if (count($this->errors) > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Set the error HTML
	 *
	 * @param string $err_name The field name to use as the error name.
	 * @param string $msg The error message.
	 */
	private function set_error($err_name, $msg) {
		$this->errors[$err_name] = "<div class='alert alert-danger error mt-3' role='alert'>". $this->format_msg($err_name, $msg) ."</div>";
	}

	/**
	 * Format and construct a user friendly error message depending on the error name.
	 *
	 * @param string $err_name The error name.
	 * @param string $msg The error message.
	 * @return string `$format_msg`: The formatted message.
	 */
	private function format_msg($err_name, $msg) {
		if (strpos($err_name, "confirmation") !== false) {
			$format_msg = "Confirmation";
		}
		elseif (strpos($err_name, "footage_datetime") !== false) {
			$format_msg = "A reason for the difference in date/time";
		}
		elseif (strpos($err_name, "date_time") !== false) {
			$format_msg = str_replace("_", "/", $err_name);
		}
		elseif (strpos($err_name, "incident") !== false) {
			$format_msg = str_replace("incident", "", $err_name);
		}
		else {
			$format_msg = $err_name;
		}

		$format_msg = trim(str_replace("_", " ", $format_msg));

		if ($err_name === "footage_upload") {
			$format_msg = $msg;
		}
		else {
			// Capitalise the first character of the message for user output.
			$format_msg = ucfirst($format_msg . " $msg");
		}
		

		return $format_msg;
	}

	/**
	 * Sanitise and Format the values.
	 */
	private function sanitise_values() {
		// Change the postcode to all uppercase.
		$this->fields["postcode"] = strtoupper($this->fields["postcode"]);

		// Preserve paragraphs in the vehicle in relation and description fields.
		$this->fields["vehicle_location_in_relation"] = nl2br($this->fields["vehicle_location_in_relation"]);
		$this->fields["incident_description"] = nl2br($this->fields["incident_description"]);

		if (empty($this->fields["vehicle_make"])) {
			$this->fields["vehicle_make"] = "Unknown";
		}
		if (empty($this->fields["vehicle_model"])) {
			$this->fields["vehicle_model"] = "Unknown";
		}
		if (empty($this->fields["vehicle_colour"])) {
			$this->fields["vehicle_colour"] = "Unknown";
		}
		
		foreach ($this->fields as $key => $value) {
			// Encode all quotes and HTML special characters.
			$this->fields[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');
		}
	}
}
