<?php

use \Speedy\Loader;
use \Speedy\Session;
use \Cms\Models\Module;
use \Cms\Models\Event\Manager as EventManager;
use \Cms\Lib\Module\Site as SiteModules;
use \Cms\Models\Theme;

class App extends \Speedy\App {

	protected $_name = "Cms";


	protected function initApp() {
		Session::instance();
		EventManager::dispatch('bootstrap');
		
		$theme = Theme::currentTheme();
		Loader::instance()->pushPathToNamespace("cms.views", $theme['fullpath'] . DS . 'views');
		Loader::instance()->registerNamespace("cms.blocks",  [APP_PATH . DS . 'Blocks', $theme['fullpath'] . DS . 'blocks']);
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