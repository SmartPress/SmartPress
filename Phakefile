<?php
require_once "public" . DIRECTORY_SEPARATOR . "defines.php";
define ("SPEEDY_PATH", VENDOR_PATH . DS . "SpeedyPHP" . DS . "Framework");

$corePhake = SPEEDY_PATH . DS . 'tasks' . DS . 'Phakefile.php';
if (!file_exists($corePhake)) {
	trigger_error("Missing required files!? Try running 'composer install' first");
}

require_once $corePhake;


?>