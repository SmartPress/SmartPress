<?php

use \Speedy\Loader;
use \Speedy\Session;

class App extends \Speedy\App {

	protected $_name = "Cms";


	protected function initApp() {
		Session::start();
	}
	
}

?>