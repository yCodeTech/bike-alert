<?php

define("ROOT_DIR", dirname(__DIR__, 1));
define("VIEWS_DIR", ROOT_DIR . "/views/");

define("SITE_NAME", "Bike Alert");

define("NAV_LINKS", [
	"home" => "Home",
	"report" => "Report Traffic Incident",
	"login" => "Login",
	"logout" => "Logout",
	"view_report" => "View Report"
]);
define("MAX_UPLOAD_FILE_SIZE", 1024); // 1024MB = 1GB

define("ALLOWED_FILE_TYPES", [
	"video/mp4" => "mp4",
	"video/quicktime" => "mov",
	"video/x-msvideo" => "avi"
]);
