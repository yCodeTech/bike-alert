<?php

namespace Controllers;

class CameraFittings extends BaseController {
	/**
	 * Get the camera fittings data from the database.
	 *
	 * Accesses and uses the BaseController's (parent) model property.
	 */
	public function display_camera_fittings() {
		return $this->model->select_all("camera_fittings");
	}
}
