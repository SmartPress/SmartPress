<?php 
namespace Cms\Lib\Module;


use \Cms\Models\Module;
use \Cms\Lib\Module\Exception as MException;
use \Speedy\Singleton;
use \Speedy\Cache;
use \Speedy\Utility\Inflector;
use \Speedy\Utility\Set;
use \Speedy\Loader;
use \Speedy\Router\Draw;
use \App;

Class Site extends Singleton {
	
	/**
	 * Site modules in array
	 * @var array
	 */
	public $modules;
	
	
	
	/**
	 * Load all modules for the current site
	 * @return array
	 */
	public function loadModules() {
		$siteModules	= Cache::read("modules");
		if (empty($siteModules)) {
			$unfiltered	= Module::findActives();
			$siteModules	= array();
				
			foreach ($unfiltered as $module) {
				$config	= $module->config();
					
				$siteModules[(string) $config->code]	= array_merge(Set::toArray($config), array('file_path' => $module->filePath()));
			}
				
			Cache::write('modules', $siteModules);
		}
		
		return $siteModules;
	}
	
	/**
	 * Getter for modules
	 * @return array $this->modules
	 */
	public function modules() {
		if (!$this->modules) {
			$this->modules = $this->loadModules();
		}
		
		return $this->modules;
	}
	
	/**
	 * Load all modules
	 * @return object instance of Cms\Lib\Module\Site
	 */
	public static function load()	{	
		$self = self::instance();
		
		foreach ($self->modules() as $module) {
			Loader::instance()->registerNamespace(
					Inflector::underscore($module['namespace']), 
					$module['file_path']);
			Loader::instance()->pushPathToNamespace(
					App::instance()->ns() . '.views', 
					$module['file_path'] . DS . 'Views');
			/*
			if (!count($module['routes'])) {
				continue;
			}
			
			foreach ($module['routes'] as $route => $settings) {
				if (!method_exists($draw, $route)) {
					continue;
				}
				
				if ($route == 'resources' && !isset($settings['name'])) {
					continue;
				}
				
				if ($settings['namespace']) {
					$draw->_namespace($settings['namespace'], function() use ($route, $settings, $draw, $module) {
						if ($route == 'resources') {
							$draw->resources($settings['name'], [ 'namespace' => $module['namespace'] ]);
						} 
					});
				}
			}*/
		}
		
		return $self;
	}
	
	/*public function getModulesByFeature($name) {
		$modules	= array();
		foreach ($this->modules as $module) {
			if (!in_array((string) $name, $module['features']['feature'])) {
				$modules[]	= $module;
			}
		}
		
		return $modules;
	}*/
	
	/**
	 * @param string $code
	 * @return boolean true if module present
	 */
	public static function hasModule($code) {
		$self	= self::instance();
		return (is_string($code)) ? isset($self->modules[$code]) : false;
	}
	
	/** 
	 * @return array of site modules
	 */
	public static function all() {
		$self	= self::instance();
		return $self->modules();
	}
	
	public static function allPaths() {
		$self	= self::instance();
		$paths	= [];
		
		foreach ($self->modules as $code => $info) {
			$paths[]	= $info['file_path'];
		}
		
		return $paths;
	}
	
	/**
	 * Getter for module cache
	 * @param string $code
	 * @return array
	 */
	public static function module($code) {
		return (self::hasModule($code)) ? self::instance()->modules[$code] : null;
	}
	
	/*public function modules() {
		if ($this->modules) return $this->modules;
		self::load();
		
		return $this->modules;
	}*/
}

?>