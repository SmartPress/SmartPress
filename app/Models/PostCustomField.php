<?php
namespace Cms\Models;


use Speedy\Cache;
use Speedy\Set;
use Speedy\Model\ActiveRecord\Base;

class PostCustomField extends Base {
	
	const CacheName = "post_custom_fields";
	
	static $after_save = ['flushCache'];
	
	static $after_destroy = ['flushCache'];
	
	
	
	public function flushCache() {
		Cache::clear(ConfigManager::CacheName);
	}
	
	static function all() {
		$fields = Cache::read(self::CacheName);
		if (empty($fields)) {
			$fields = parent::all();
			Cache::write(self::CacheName, (array) $fields);
		} else {
			$fields = new Set($fields);
		}
		
		return $fields;
	}
	
}

?>