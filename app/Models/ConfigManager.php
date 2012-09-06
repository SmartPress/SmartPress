<?php
namespace Cms\Models;


use \Cms\Models\Config;
use \Speedy\Cache;
use \Speedy\Singleton;

class ConfigManager extends Singleton {
	
	use \Speedy\Utility\ArrayAccess;
	
	const CacheName = "configs";
	
	private static $_configs;
	
	
	
	public static function get($name = null) {
		return self::instance()->_get($name);
	}
	
	public function _get($name = null) {
		if (self::$_configs) {
			if (!$name) return self::$_configs;
			return $this->__dotAccess($name, self::$_configs);
		}
		
		$configs = Cache::read(self::CacheName);
		if (empty($configs)) {
			$configs = Config::all(['select' => 'name, value']);
		
			$configs = $this->mutateDataWithKeyValue($configs, 'name', 'value');
			Cache::write(self::CacheName, $configs);
		}
		
		self::$_configs = $configs;
		return ($name) ? $this->__dotAccess($name, self::$_configs) : self::$_configs;
	}
	
}
?>