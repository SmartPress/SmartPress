<?php
require_once('Loader.php');
require_once('Routes.php');

import('speedy.app');
use \Speedy\Loader;
use \Speedy\Session;

class App extends \Speedy\App {

	protected $_name = "Cms";


	protected function initApp() {
		Session::start();
	}
	
}

?>