<?php

use \Speedy\Loader;
use \Speedy\Session;
use \Cms\Models\Module;
use \Cms\Models\Event\Manager as EventManager;
use \Cms\Lib\Module\Site as SiteModules;

class App extends \Speedy\App {

	protected $_name = "Cms";


	protected function initApp() {
		Session::start();
		EventManager::dispatch('bootstrap');
	}
	
	protected function initModules() {
		SiteModules::load();
		//debug(SiteModules::all());
	}
	
	protected function initEvents() {
		EventManager::addListener('admin_configs_update_theme', [
					'class'	=> '\\Cms\\Models\\Theme',
					'method'=> 'updateTheme'
				]);
	}
	
}

?>