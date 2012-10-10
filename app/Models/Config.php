<?php
namespace Cms\Models;


use \Speedy\Cache;
use \Cms\Models\Config\Manager as ConfigManager;

class Config extends \Speedy\Model\ActiveRecord {
	
	static $after_save = ['flushCache'];
	
	static $after_destroy = ['flushCache'];
	
	
	public function flushCache() {
		Cache::clear(ConfigManager::CacheName);
	}
	
}

?>