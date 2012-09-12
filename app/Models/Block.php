<?php
namespace Cms\Models;


use \Speedy\Cache;
use \Speedy\Model\ActiveRecord\Base;

class Block extends Base {
	
	const CacheName	= "blocks";
	
	static $after_save = ['flushCache'];
	
	static $after_destroy = ['flushCache'];
	
	
	public function flushCache() {
		Cache::clear(self::CacheName);
	}
	
}

?>