<?php

namespace Controllers;

use Models\BaseModel;
use Models\Login;

class BaseController {
	protected $model;

	function __construct() {
		$this->model = new BaseModel();
	}

	/**
	 * Load the specified view.
	 *
	 * @param string $view_name The name of the view to include.
	 * @param mixed $data The data from the database.
	 *
	 * @uses global include_view
	 */
	public function load_view($view_name, $data = null) {
		include_view($view_name, $data);
	}

	/**
	 * Load the report
	 *
	 * @param array $array An array of form errors and the field values to postback to the form.
	 */
	public function load_report($array = []) {
		$data = (new CameraFittings())->display_camera_fittings();
	
		$this->load_view("report", ["fittings" => $data, ...$array]);
	}
	
	/**
	 * Get the incident report from the database
	 *
	 * @param string $id The incident report reference
	 */
	public function get_report($id) {
		return $this->model->get_report($id);
	}
	
	/**
	 * Validate the login details.
	 */
	public function login() {
		return (new Login())->validate();
	}
}
