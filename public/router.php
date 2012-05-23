<?php 
defined("STDOUT") or define("STDOUT", fopen("php://stdout", "w"));

function output($str = "") {
	fwrite(STDOUT, $str . "\n");
}

//print_c('URI - ' . $_SERVER['REQUEST_URI']);
if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $_SERVER['REQUEST_URI'])) {
	return false;
} else {
	if (!isset($_GET['url'])) {
		$_GET['url']	= $_SERVER['PATH_INFO'];
	}
	// echo "<pre>";
	// print_r($_SERVER);
	// echo "</pre>";
	include_once "index.php";
}
?>