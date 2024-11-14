<?php

namespace Models;

class SubmitReport extends BaseModel {
	private $report_ref;
	private $fields;
	private $file;
	private $file_name;

	function __construct($report_ref, $fields, $file) {
		$this->report_ref = $report_ref;
		$this->fields = $fields;
		$this->file = new FileUpload($file, $this->report_ref);

		parent::__construct();
	}

	/**
	 * Submit the report
	 */
	public function submit() {
		// var_dump($this->fields);

		$this->file_name = $this->file->upload();

		if ($this->submit_user()) {
			return $this->submit_report();
		}
		else {
			return false;
		}
	}

	/**
	 * Submit the user details into the user table.
	 */
	private function submit_user() {
		$user = [
			"id" => null,
			"firstname" => $this->fields["firstname"],
			"lastname" => $this->fields["lastname"],
			"email" => $this->fields["email"],
			"mobile_number" => $this->fields["mobile"],
			"phone_number" => $this->fields["phone"],
			"dob" => $this->fields["date_of_birth"],
			"address_line_1" => $this->fields["address_line_1"],
			"address_line_2" => $this->fields["address_line_2"],
			"town_city" => $this->fields["town_or_city"],
			"county" => $this->fields["county"],
			"postcode" => $this->fields["postcode"],
			"pref_contact_method" => $this->fields["preferred_contact_method"]
		];

		return parent::insert("user", $user);
	}

	/**
	 * Submit the report details into the incident table
	 */
	private function submit_report() {
		$data = [
			"user_id" => parent::get_last_id(),
			"incident_id" => $this->report_ref,
			"location_rd_city" => $this->fields["incident_location"],
			"location_county" => $this->fields["incident_county"],
			"police_force_name" => $this->fields["police_force_name"],
			"date_time" => $this->fields["date_time"],
			"vehicle_make" => $this->fields["vehicle_make"],
			"vehicle_model" => $this->fields["vehicle_model"],
			"vehicle_reg" => $this->fields["vehicle_registration"],
			"vehicle_colour" => $this->fields["vehicle_colour"],
			"location_details_in_relation" => $this->fields["vehicle_location_in_relation"],
			"incident_description" => $this->fields["incident_description"],
			"camera_fitting_id" => $this->fields["camera_fitting"],
			"footage_filename" => $this->file_name,
			"footage_wrong_datetime_reason" => $this->fields["footage_datetime_difference_reason"]
		];
		
		return parent::insert("incident_report", $data);
	}
}
