<?php
namespace SmartPress\Models;


use Speedy\Cache;

class Block extends \Speedy\Model\ActiveRecord {
	
	const CacheName	= "blocks";
	
	static $after_save = ['flushCache'];
	
	static $after_destroy = ['flushCache'];
	
	private $_params;
	
	
	public function flushCache() {
		Cache::clear(self::CacheName);
	}
	
	public function set_params($params) {
		if (is_array($params) && isset($params['only']))
			$params['only']	= explode(',', str_replace(' ', '', $params['only']));
		
		if (is_array($params) && isset($params['except']))
			$params['except']	= explode(',', str_replace(' ', '', $params['except']));
		
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
