<?php 
defined("STDOUT") or define("STDOUT", fopen("php://stdout", "w"));

function output($str = "") {
	if (is_array($str)) $str = print_r($str, true);
	fwrite(STDOUT, $str . "\n");
}

if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $_SERVER['REQUEST_URI'])) {
	return false;
} else {
	if (!isset($_GET['url']) && isset($_SERVER['PATH_INFO'])) {
		$_GET['url']	= $_SERVER['PATH_INFO'];
	}
	
	include_once "index.php";
}
?>