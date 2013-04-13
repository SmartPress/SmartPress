<?php
namespace SmartPress\Models;


use Speedy\Cache;
use SmartPress\Models\Config\Manager as ConfigManager;

class Config extends \Speedy\Model\ActiveRecord {
	
	static $after_save = ['flushCache'];
	
	static $after_update = ['flushCache'];

	static $after_destroy = ['flushCache'];
	
	const BlogTagLineName	= 'blog/tag_line';

	const HomeTypeName		= 'blog/home/type';

	const HomeSingleName	= 'blog/home/single_id';

	const DefaultTitleName	= 'blog/default/title';

	const TitleFormat 		= 'blog/title/format';

	
	public function flushCache() {
		Cache::clear(ConfigManager::CacheName);
	}
	
}

?>
