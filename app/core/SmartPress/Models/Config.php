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

	const PostUrlSchema		= 'blog/post_url_schema';

	const GACode 	= 'blog/ga_code';

	
	public function flushCache() {
		Cache::clear(ConfigManager::CacheName);
	}

	public function set_value($val) {
		$this->assign_attribute('value', htmlentities($val));
		return $this;
	}

	public function get_value() {
		return $this->read_attribute('value');
	}

	public function get_html_value() {
		return html_entity_decode($this->read_attribute('value'));
	}
	
}


