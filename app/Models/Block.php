<?php
namespace Cms\Models;


use \Speedy\Cache;
use \Speedy\Model\ActiveRecord\Base;

class Block extends Base {
	
	const CacheName	= "blocks";
	
	static $after_save = ['flushCache'];
	
	static $after_destroy = ['flushCache'];
	
	private $_params;
	
	
	public function flushCache() {
		Cache::clear(self::CacheName);
	}
	
	public function set_params($params) {
		$this->assign_attribute('params', serialize((array) $params));
		return $this;
	}
	
	public function get_params() {
		if (!$this->_params) {
			$this->_params = unserialize($this->read_attribute('params'));
		}
		
		return $this->_params;
	}
}

?>