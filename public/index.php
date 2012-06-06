<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";
require_once "defines.php";

if (!include(CONFIG_PATH . DS . 'App.php')) {
	trigger_error("Could not find App class for current application, please check that app file is in CONFIG_PATH/App.php");
}

$app	= App::instance();
$app->bootstrap()->run();

?>