<?php

namespace Models;

class Login extends BaseModel {
	private $fields;

	private $errors = [];

	function __construct() {
		$this->fields = $_POST['login'] ?? "";

		parent::__construct();
	}
	
	/**
	 * Validate the login
	 *
	 * @return array|string An array of error and postback value, or the incident reference.
	 */
	public function validate() {
		
		if (!empty($this->fields)) {
			$this->errors = [];

			foreach ($this->fields as $key => $field) {
				$this->fields[$key] = trim($field);


				if (empty($field)) {
					$this->set_error($key, "This is required");
				}
			}

			if (!$this->is_valid_ref()) {
				$this->set_error("incident_ref", "Invalid incident reference");
			}
		}

		if ($this->has_errors()) {
			return [
				"errors" => $this->errors,
				"postback_value" => $this->fields
			];
		}

		return $this->fields["incident_ref"];
	}

	/**
	 * Set the error HTML
	 *
	 * @param string $err_name The field name to use as the error name.
	 * @param string $msg The error message.
	 */
	private function set_error($err_name, $msg) {
		$this->errors[$err_name] = "<div class='alert alert-danger error mt-3' role='alert'>". $msg ."</div>";
	}

	/**
	 * Is there any errors
	 */
	private function has_errors() {
		if (count($this->errors) > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Is the user-inputted reference a valid existing reference
	 *
	 * @return boolean
	 */
	private function is_valid_ref() {
		$where = [
			"incident_id" => $this->fields["incident_ref"]
		];
		$result = parent::select_one("incident_report", "incident_id", $where);

		if ($result) {
			return true;
		}
		return false;
	}
}
