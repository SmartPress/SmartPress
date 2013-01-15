<?php

use Speedy\Loader;
use Speedy\Session;
use SmartPress\Models\Module;
use SmartPress\Models\Event\Manager as EventManager;
use SmartPress\Lib\Module\Site as SiteModules;
use SmartPress\Models\Theme;

class App extends \Speedy\App {

	protected $_name = "SmartPress";


	protected function initApp() {
		Session::instance();
		EventManager::dispatch('bootstrap');
		
		$theme = Theme::currentTheme();
		Loader::instance()->pushPathToNamespace("smart_press.views", $theme['fullpath'] . DS . 'views');
		Loader::instance()->registerNamespace("smart_press.blocks",  [APP_PATH . DS . 'Blocks', $theme['fullpath'] . DS . 'blocks']);
	}
	
	protected function initModules() {
		SiteModules::load();
		//debug(SiteModules::all());
	}
	
	protected function initEvents() {
		EventManager::addListener('admin_configs_update_theme', [
					'class'	=> '\\SmartPress\\Models\\Theme',
					'method'=> 'updateTheme'
				]);
	}
	
}

?>