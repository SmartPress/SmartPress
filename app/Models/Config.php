<?php
namespace Cms\Models;


use \Speedy\Cache;
use \Speedy\Model\ActiveRecord\Base;
use \Cms\Models\ConfigManager;

class Config extends Base {
	
	static $after_save = ['flushCache'];
	
	static $after_destroy = ['flushCache'];
	
	
	public function flushCache() {
		Cache::clear(ConfigManager::CacheName);
	}
	
}

?>