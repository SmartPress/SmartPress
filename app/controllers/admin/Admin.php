<?php
namespace Cms\Controllers\Admin;

use \Cms\Controllers\Application;
use \Cms\Lib\Module\Site as SiteModules;
use \Speedy\Cache;

class Admin extends Application {

	public $layout	= "admin/default";

	protected $beforeFilter = ['adminMenus'];
	
	
	
	protected function adminMenus() {
		$this->menus = Cache::read("module_menus");
		if (!empty($this->menus)) {
			return;
		} 
		
		$menus = [
			'modules' => [],
			'settings' => []
		];
		foreach (SiteModules::all() as $module) {
			if (!isset($module['menus']) || empty($module['menus']['link'])) {
				continue;
			}
			
			foreach ($module['menus']['link'] as $link) {
				if (!isset($menus[$link['type']])) {
					continue;
				}
				
				$menus[$link['type']][] = [
					'label' => $link['label'],
					'url'	=> $link['url']
				];
			}
		}
		
		$this->menus = $menus;
		Cache::write("module_menus", $menus);
	}
}

?>