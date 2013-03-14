<?php 
namespace SmartPress\Lib\View;


use Speedy\Loader;
use Speedy\Utility\Inflector;
use SmartPress\Lib\Module\Site;

class Php extends \Speedy\View\Php {
	
	protected function __loadMixins() {
		$paths['smart_press.helpers'] = Loader::instance()->path('smart_press.helpers')[0];

		foreach (Site::all() as $module) {
			$helperNs = $module['inflected_namespace'] . '.helpers';
			$paths[$helperNs] = Loader::instance()->path($helperNs)[0];
		}

		$helpers	= array();
		foreach ($paths as $ns => $path) {
			$files	= rglob('*.php', 0, $path);

			foreach ($files as $file) {
				$info	= pathinfo($file);
				$dir	= $info['dirname'];
				
				$import = $ns;
				if (preg_match("#{$path}/(.*)#", $dir, $matches)) {
					$import	.= '.' . str_replace('/', '.', $matches[1]);
				} 
				
				$import .= '.' . Inflector::underscore($info['filename']);
				$helpers[]	= $import;
			}
		}
		
		return $helpers;
	} 
	
}
?>
