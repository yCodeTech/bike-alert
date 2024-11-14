<?php

namespace Controllers;

use Models\SubmitReport;

class Report extends BaseController {
	private $report_ref;
	private $fields;
	private $file;
	protected $model;
	private $error;

	function __construct($fields, $file) {
		$this->report_ref = $this->generate_report_ref(10);
		$this->fields = $fields;
		$this->file = $file;
		$this->model = new SubmitReport($this->report_ref, $this->fields, $this->file);
	}
	
	/**
	 * Submit to the model
	 */
	public function submit() {
		if (!$this->model->submit()) {
			echo "Something went wrong. Couldn't submit into the database. Please try again.";
			return false;
		}

		return true;
	}

	/**
	 * Get the report reference
	 */
	public function get_report_ref() {
		return $this->report_ref;
	}

	/**
	 * Generate a new random report reference
	 */
	private function generate_report_ref($length) {
		// Create an array with A-Z and a-z characters
		$A_Z = range("A", "Z");
		$a_z = range("a", "z");
		// Create an array with 0-9 characters
		$nums = range("0", "9");
		// Merge both arrays and implode them to a string.
		$chars = implode('', array_merge($A_Z, $a_z, $nums));
		$report_ref = "";

		// Loop until desired length is hit...
		for ($i = 0; $i < $length; $i++) {
			// Add a random character to the ref.
			$report_ref .= $chars[rand(0, strlen($chars) - 1)];
		}

		return $report_ref;
	}
}
