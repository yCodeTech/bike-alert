<?php

namespace Models;

class FileUpload {
	private $file;
	private $file_path;
	private $file_name;
	private $file_extension;

	private $upload_dir;
	private $upload_file_path;
	private $report_ref;

	function __construct($file, $report_ref) {

		$this->file = $file;
		$this->file_path = $this->file["tmp_name"];
		$this->report_ref = $report_ref;

		$file_info = finfo_open(FILEINFO_MIME_TYPE);
		$file_type = finfo_file($file_info, $this->file_path);
		$this->file_extension = "." . ALLOWED_FILE_TYPES[$file_type];
		$this->upload_dir = get_proj_root_dir() . "/uploads";

		$this->file_name = "ref_" . $this->report_ref . "_evidence_footage" . $this->file_extension;
		$this->upload_file_path = $this->upload_dir . "/" . $this->file_name;
	}

	/**
	 * Upload the footage, ie.
	 * move the file to a permanent storage directory.
	 *
	 * @return string|false The filename
	 */
	public function upload() {
		// Move uploaded file to destination.
		if (move_uploaded_file($this->file_path, $this->upload_file_path)) {
			return $this->file_name;
		}
		return false;
	}
}
