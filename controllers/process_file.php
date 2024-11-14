<?php

namespace Controllers;

class ProcessFile {
	private $error;
	private $file;
	private $file_name;
	private $file_path;
	private $file_size;
	private $file_type;

	function __construct($file) {
		$this->error = "";
		$this->file = $file;
		$this->file_name = $this->file["name"];
		$this->file_path = $this->file["tmp_name"];
		$this->file_size = $this->convert_bytes_to_mb(filesize($this->file_path));
	}

	/**
	 * Validate the footage.
	 */
	public function validate() {
		// Required
		if ($this->is_empty()) {
			$this->error = "Footage upload is required";
		}
		else {
			$file_info = finfo_open(FILEINFO_MIME_TYPE);
			$this->file_type = finfo_file($file_info, $this->file_path);

			// File empty
			if ($this->is_file_size_empty()) {
				$this->error = "The file seems to be empty with a size of $this->file_size";
			}
			// File too large
			elseif ($this->is_too_large()) {
				$this->error = "The file is too large with a size of {$this->file_size}MB. The max filesize is ". MAX_UPLOAD_FILE_SIZE . "MB";
			}
			// File the correct type
			elseif (!$this->is_file_types_accepted()) {
				$this->error = "Incorrect file type. Please only upload videos of the allowed types.";
			}
		}
	}

	/**
	 * Is there any errors?
	 */
	public function has_error() {
		return !empty($this->error);
	}

	/**
	 * Get the error
	 */
	public function get_error() {
		return $this->error;
	}

	/**
	 * Get the file
	 */
	public function get_file() {
		return $this->file;
	}

	/**
	 * Is value empty
	 */
	private function is_empty() {
		return empty($this->file_name);
	}

	/**
	 * Is file empty
	 */
	private function is_file_size_empty() {
		return $this->file_size == 0;
	}

	/**
	 * Is file too large
	 */
	private function is_too_large() {
		return $this->file_size > MAX_UPLOAD_FILE_SIZE;
	}

	/**
	 * Is file an accepted type
	 */
	private function is_file_types_accepted() {
		return in_array($this->file_type, array_keys(ALLOWED_FILE_TYPES));
	}

	/**
	 * Convert bytes to megabytes for human readability
	 */
	private function convert_bytes_to_mb($bytes) {
		$mb = $bytes / (1024 * 1024);
		// Make the mb to 2 decimal places by manipulating the string.
		// From https://stackoverflow.com/a/34073285/2358222
		return preg_replace('/\.(\d{2}).*/', '.$1', $mb);
	}
}
