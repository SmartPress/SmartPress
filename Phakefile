<?php
require_once "public" . DIRECTORY_SEPARATOR . "defines.php";
define ("SPEEDY_PATH", VENDOR_PATH . DS . "speedy-php" . DS . "framework");

$corePhake = SPEEDY_PATH . DS . 'tasks' . DS . 'Phakefile.php';
if (!file_exists($corePhake)) {
	trigger_error("Missing required files!? Try running 'composer install' first");
}

require_once $corePhake;

$glob = MODULES_PATH . DS . '*' . DS . 'Etc' . DS . 'tasks.php';
foreach (glob($glob) as $tasks) {
	require_once $tasks;
}

?>