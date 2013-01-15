<?php
namespace SmartPress\Models;


use \Speedy\Cache;
use \SmartPress\Models\Config\Manager as ConfigManager;

class Config extends \Speedy\Model\ActiveRecord {
	
	static $after_save = ['flushCache'];
	
	static $after_destroy = ['flushCache'];
	
	
	public function flushCache() {
		Cache::clear(ConfigManager::CacheName);
	}
	
}

?>
