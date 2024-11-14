<?php
/**
 * Find and require Composer's autoload.php file.
 */
function composer_autoload() {
	// Get the path of the composer autoload. Dirname retrieves the parent of this file's directory.
	// So __DIR__ gets the includes directory, and dirname retrieves the parent of that.
	// ie. The project home directory.
	$composerAutoload = realpath(dirname(__DIR__, 1).'/vendor/autoload.php');
	if (!file_exists($composerAutoload)) {
		die('Error locating autoloader. Please run <code>composer install</code>.');
	}
	require $composerAutoload;
}

/**
 * Set PHP error reporting
 *
 * @param int $value The value of error reporting. `1` === on, `0` === off
 */
function set_php_error_reporting($value) {
	ini_set('display_errors', $value);
	ini_set('display_startup_errors', $value);
	error_reporting(E_ALL);
}

/**
 * Get the project root directory - the full absolute path (including drive letter).
 */
function get_proj_root_dir() {
	return ROOT_DIR;
}

/**
 * Include the specified view.
 *
 * @param string $view_name The name of the view to include.
 * @param mixed $data The data from the database.
 */
function include_view($view_name, $data = null) {
	if ($data) {
		extract($data);
	}
	include VIEWS_DIR.$view_name . ".php";
}

/**
 * ob_start() atop this file stops any HTML/output reaching the page
 * and stores the output internally.
 * This is then used to be able to change the title of the page
 * before sending everything to the page.
 *
 * Code from a previous assignment accessible at https://github.com/yCodeTech/Hotels4U
 * which was based on an answer from StackOverflow: https://stackoverflow.com/a/32337830/2358222
 *
 * @param string $page_title The page title
 */
function page_buffer($page_title) {
	// Get the contents of the internal buffer and store it as a variable to be.
	$buffer = ob_get_contents();
	// Erase and disable the internal output buffer.
	ob_end_clean();

	$buffer = set_doc_title($page_title, $buffer);

	// Return the buffer output to the function.
	return $buffer;
}

/**
 * Set the page title.
 *
 * @param string $page_title The page title
 * @param string $buffer The buffered/stored page HTML
 * @return string A modified buffered/stored page HTML with the added page title
 */
function set_doc_title($page_title, $buffer) {
	// If page title is set, get the title and add the site/company name on to it,
	// otherwise just set the title as the site/company name.
	$page_title = (!empty($page_title)) ? $page_title . " | " . SITE_NAME : SITE_NAME;
	// In the buffer, find and replace the <title> tag with the new page title.
	return str_replace("<title></title>", "<title>{$page_title}</title>", $buffer);
}

/**
 * Create the HTML for the nav items.
 *
 * @return string $html
 */
function create_nav_items() {
	$html = "";
	
	foreach (NAV_LINKS as $link_name => $link_text) {
		if (isset($_SESSION["incident_id"]) && $link_name === "login") {
			continue;
		}
		elseif (!isset($_SESSION["incident_id"]) && ($link_name === "logout" || $link_name === "view_report")) {
			continue;
		}

		$html .= '<li class="nav-item' . set_nav_link_active($link_name) . '">
					<a href="/'. $link_name .'" class="nav-link">' . $link_text . '</a>
				</li>';
	}
	return $html;
}

/**
 * Set the nav link active state if the url action params match the link name.
 *
 * @param string $link_name The link/page name, as used in the action params.
 * @return string An `active` class
 */
function set_nav_link_active($link_name) {
	$server_uri = $_SERVER['REQUEST_URI'];
	
	if ($server_uri === "/$link_name") {
		return " active";
	}
	return "";
}

/**
 * Check if a certain select option was orignally selected before the form posted back to it's self.
 * If the certain option matches the postback value, then add a selected attribute.
 *
 * @param string $name The name of the field
 * @param array $postback_value The values posted back to the form after submit.
 */
function is_selected($name, $postback_value) {
	if (!empty($postback_value)) {
		if ($postback_value == $name) {
			return "selected";
		}
	}
	return "";
}

/**
 * Check if a specific checkbox was originally checked before the form posted back to it's self.
 * If the checkbox matches the postback value, then add a data-checked attribute to use via JS.
 *
 * @param string $name The name of the field
 * @param array $postback_value The values posted back to the form after submit.
 */
function is_checked($name, $postback_value) {
	if (isset($postback_value)) {
		if ($postback_value == $name) {
			return "data-checked='true'";
		}
	}
	return "";
}

/**
 * Check if the incident_id session is set.
 */
function is_session_set() {
	return isset($_SESSION["incident_id"]);
}

/**
 * Get the 403 error title.
 */
function get_403_title() {
	return "403 - Forbidden";
}

/**
 * Load the 403 error view.
 */
function display_403($controller) {
	http_response_code(403);
	$controller->load_view("403");
}

?>
