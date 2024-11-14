<?php
/** Front Controller */

namespace BikeAlert;

session_start();

use Controllers\BaseController;
use Controllers\FormValidation;
use Controllers\Report;
use Controllers\Ajaxing;

$ajaxing = false;

// Start output buffering
// (prevents all html output from reaching the page and instead stores it internally).
// Used for displaying individual page doc titles.
ob_start();

require_once "./includes/constants.php";
require_once "./includes/functions.php";

composer_autoload();

// Autoload Controllers and Models via their directory and namespacing.
spl_autoload_register(function ($class_name) {
	// Because the default function of spl_autoload make the class names lowercase,
	// which works on Windows because it's filesystem is case-insensitive, so a lowercase classname
	// like basecontroller, the function would find the definition in BaseController.php,
	// basecontroller.php, BASEcontroller.php, etc.
	// However, this doesn't work on live servers using case-sensitive systems like Linux.
	// So they would literally be looking for a lowercased file name, and if not found
	// everything breaks.
	//
	// To get around this, we need to rename the class files to lowercase with an underscore (the latter for readability), then replace the default function...

	// All controllers and models are now lowercased with underscores, like base_controller.php
	
	// The following processes the class names that PHP has found, and converts them to the format for the filenames, so the files can be loaded.

	// Find all uppercase character except the first, and add an underscore before it.
	$class_name = preg_replace('/\B([A-Z])/', '_$1', $class_name);
	// Replace all backslashes (\) with a forwardslash (/)
	$class_name = preg_replace('/\\\/', '/', $class_name);
	// Convert to all lowercase.
	$class_name = strtolower($class_name). ".php";
	
	require_once($class_name);
});

// Set the Error reporting
set_php_error_reporting(1);

// Init the base controller
$controller = new BaseController();

$page_title = "";

if ($_SERVER['REQUEST_URI'] != "/") {
	$server_uri = $_SERVER['REQUEST_URI'];
}
else {
	$server_uri = "/home";
	header("Location: $server_uri");
}

$controller->load_view("header");

/**
 * Home
 */
if ($server_uri === "/home") {
	$controller->load_view("home");
}
/**
 * Report
 */
elseif ($server_uri === "/report") {
	$page_title = "Report an Incident";
	$controller->load_report();
}
/**
 * Report Submit - Validation
 */
elseif ($server_uri === "/report_submit") {
	$page_title = "Report an Incident";
	
	$data = (new FormValidation())->validate();

	if ($data) {
		extract($data);
		$report = new Report($fields, $file);

		if ($report->submit()) {
			$_SESSION["incident_id"] = $report->get_report_ref();
			$_SESSION["submitted"] = true;
			header("Location: /view_report");
		}
	}
}
/**
 * View Report
 */
elseif ($server_uri === "/view_report") {
	if (!is_session_set()) {
		$page_title = get_403_title();
		display_403($controller);
	}
	else {
		$data = $controller->get_report($_SESSION["incident_id"]);
	
		$controller->load_view("view_report", $data[0]);
	}
}
/**
 * Login
 */
elseif ($server_uri === "/login") {
	$page_title = "Login";
	
	$controller->load_view("login");
}
/**
 * Login Submit - Validation
 */
elseif ($server_uri === "/login_submit") {
	$page_title = "Login";

	$login = $controller->login();

	if (gettype($login) === "array") {
		$controller->load_view("login", $login);
	}
	else {
		$_SESSION["incident_id"] = $login;
		header("Location: /view_report");
	}
}
/**
 * Logout
 */
elseif ($server_uri === "/logout") {
	// Unset all session keys.
	session_unset();

	$_SESSION["loggedout"] = true;

	header("Location: /login");
}

/**
 * Ajax
 *
 * Added after graduated uni.
 */
elseif ($server_uri === "/ajax") {
	$ajaxing = true;
	// Disable and erase the output buffering, we don't need it for Ajax.
	ob_end_clean();

	new Ajaxing();
}

/**
 * 404 Not Found
 */
else {
	http_response_code(404);
	$page_title = "404 - Not Found";

	$controller->load_view("404");
}

// Only if we're not Ajaxing
// Added after graduated uni.
if ($ajaxing === false) {
	$controller->load_view("footer");
	echo page_buffer($page_title);
}
