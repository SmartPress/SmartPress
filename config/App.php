<?php

use \Speedy\Loader;
use \Speedy\Session;
use \Cms\Models\Module;
use \Cms\Lib\Module\Site as SiteModules;

class App extends \Speedy\App {

	protected $_name = "Cms";


	protected function initApp() {
		Session::start();
	}
	
	protected function initModules() {
		SiteModules::load();
		//debug(SiteModules::all());
	}
	
}

?>