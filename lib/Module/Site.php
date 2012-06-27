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

Class Site extends Singleton {
	
	/**
	 * Site modules in array
	 * @var array
	 */
	public $siteModules;
	
	
	
	public static function load()	{	
		$siteModules	= Cache::read("modules");
		if (empty($siteModules)) {
			$unfiltered	= Module::findActives();
			$siteModules	= array();
			
			foreach ($unfiltered as $module) {
				$config	= $module->config();
			
				$siteModules[(string) $config->code]	= array_merge(Set::toArray($config), array('file_path' => $module->file_path));
			}
			
			Cache::write('modules', $siteModules);
		} 
		
		$self = self::instance();
		$self->siteModules	= $siteModules;
		//debug($siteModules);
		$draw	= new Draw();
		foreach ($siteModules as $module) {
			Loader::instance()->registerNamespace(Inflector::underscore($module['namespace']), $module['file_path']);
			
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
			}
		}
		
		return $self;
	}
	
	/*public function getModulesByFeature($name) {
		$modules	= array();
		foreach ($this->siteModules as $module) {
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
		return (is_string($code)) ? isset($self->siteModules[$code]) : false;
	}
	
	/** 
	 * @return array of site modules
	 */
	public static function all() {
		$self	= self::instance();
		return $self->siteModules;
	}
}

?>