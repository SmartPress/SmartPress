<?php
namespace SmartPress\Models\Config;


use SmartPress\Models\Config;
use Speedy\Cache;
use Speedy\Singleton;
use PLinq\PLinq;

class Manager extends \Speedy\Object {

	use \Speedy\Traits\Singleton;
	
	const CacheName = "configs";
	
	private static $_configs;

	private $_runTimeCache = [];
	
	
	
	public function __construct() {
		return $this;
	}
	
	public static function get($name = null) {
		return self::instance()->_get($name);
	}

	public static function has($name) {
		return self::instance()->_has($name);
	}

	public function _has($name) {
		return ($value = $this->_get($name)) ? $value : false;
	}

	public function all() {
		if (self::$_configs) {
			return self::$_configs;
		}

		$configs = Cache::read(self::CacheName);
		if (empty($configs)) {
			$configs = Config::all(['select' => 'name, value']);
		
			Cache::write(self::CacheName, $configs);		
		} 

		self::$_configs = $configs;
		return self::$_configs;
	}
	
	public function _get($name = null) {
		if (!$name)
			return $this->all();
		if (isset($this->_runTimeCache[$name]))
			return $this->_runTimeCache[$name];

		$first = PLinq::from($this->all())
			->firstOrDefault(function($config) use ($name) {
				return ($config['name'] == $name);
			});

		$this->_runTimeCache[$name] = ($first) ? $first->value : null;
		return $this->_runTimeCache[$name];
	}
	
}
?>
