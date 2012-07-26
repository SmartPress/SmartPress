<?php
namespace Cms\Controllers\Admin;

use \Cms\Controllers\Application;
use \Speedy\Cache;

class Admin extends Application {

	public $layout	= "admin/default";

	protected $beforeFilter = ['adminMenus'];
	
	
	
	protected function adminMenus() {
		$this->menus = Cache::read("module_menus");
		if (!empty($this->menus)) {
			return;
		} 
		
		
	}
}

?>