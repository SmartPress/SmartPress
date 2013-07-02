<?php

use Speedy\Loader;
use Speedy\Session;
use Speedy\Utility\Inflector;
use SmartPress\Models\Module;
use SmartPress\Models\Event\Manager as EventManager;
use SmartPress\Lib\Module\Site as SiteModules;
use SmartPress\Models\Theme;

class App extends \Speedy\App {

	protected $_name = "SmartPress";


	protected function initApp() {
		Session::instance();
		EventManager::dispatch('bootstrap');
		
		SiteModules::load();

		$theme = Theme::currentTheme();
		Loader::instance()->pushPathToNamespace("smart_press.views", $theme['fullpath'] . DS . 'views');
	}
	
	protected function initEvents() {
		EventManager::addListener('admin_configs_update_theme', [
					'class'	=> '\\SmartPress\\Models\\Theme',
					'method'=> 'updateTheme'
				]);
	}
	
	public function addPackage($name) {
		$inflected = Inflector::underscore($name);
		$groups = ['core', 'community', 'local'];

		$loader = Loader::instance();
		foreach ($groups as $group) {
			$loader->pushPathToNamespace("$inflected.controllers",	APP_PATH . DS . $group . DS . $name . DS . 'Controllers');
			$loader->pushPathToNamespace("$inflected.models", 		APP_PATH . DS . $group . DS . $name . DS . 'Models');
			$loader->pushPathToNamespace("$inflected.helpers", 		APP_PATH . DS . $group . DS . $name . DS . 'Helpers');
			$loader->pushPathToNamespace("$inflected.assets", 		APP_PATH . DS . $group . DS . $name . DS . 'Assets');
			$loader->pushPathToNamespace("$inflected.views", 		APP_PATH . DS . $group . DS . $name . DS . 'Views');
			$loader->pushPathToNamespace("$inflected.blocks",  		APP_PATH . DS . $group . DS . $name . DS . 'Blocks');
			$loader->pushPathToNamespace($inflected, 				APP_PATH . DS . $group . DS . $name);
		}
			
		$loader->setAliases(array(
			'views'			=> ["$inflected.views"],
			'helpers'		=> ["$inflected.helpers"],
			'controllers'	=> ["$inflected.controllers"],
			'models'		=> ["$inflected.models"],
			'assets'		=> ["$inflected.assets"],
		));
	}

}

?>
