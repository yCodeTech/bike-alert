<?php

/**
 * Added after graduated uni to prevent leaking of the api key in JS as it was previously.
 */

namespace Controllers;

use Controllers\BaseController;
use Composer\CaBundle\CaBundle;

/**
 * We're Ajaxing.
 */
class Ajaxing extends BaseController {
	private $post;
	private $action;

	public function __construct() {
		parent::__construct();

		$this->post = $_POST;
		$this->action = $this->post["action"];

		// Dynamic function
		$ajax_function = "ajax_{$this->action}";
		$this->$ajax_function();
	}


	/**
	 * Get the Geocode coords, and return them to the Ajax js function.
	 */
	public function ajax_get_geocode_coords() {

		// Retrieve the values from the $POST variable sent from JS.
		$rd_city_val = $this->post["rd_city_val"];
		$county_val = $this->post["county_val"];
		
		// Create an object of the Geocode API URL query vars.
		$obj = [
			"q" => "$rd_city_val+$county_val+UK",
			"api_key" => $this->model->get_geocode_key()
		];

		// Convert obj to a URL params string.
		$url_params = http_build_query($obj);
		
		// Get a response from the Geocode API.
		// Uses the fetch-php Composer package https://github.com/Thavarshan/fetch-php
		$response = fetch("https://geocode.maps.co/search?$url_params", [
			// Verify the SSL certificate with CaBundle.
			"verify" => CaBundle::getSystemCaRootBundlePath()
		]);

		if ($response->ok()) {
			$data = $response->json();
			echo json_encode($data);
		}
		else {
			echo json_encode("Error: " . $response->statusText());
		}

		exit(0);
	}
}
