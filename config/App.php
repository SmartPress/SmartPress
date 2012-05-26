<?php
require_once(SPEEDY_PATH . DS . 'Loader.php');
require_once(SPEEDY_PATH . DS . 'Router.php');

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