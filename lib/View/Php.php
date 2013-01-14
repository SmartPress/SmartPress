<?php 
namespace Cms\Lib\View;


use Speedy\Loader;
use Speedy\Utility\Inflector;
use Cms\Lib\Module\Site;

class Php extends \Speedy\View\Php {
	
	protected function __loadMixins() {
		$paths['cms.helpers'] = Loader::instance()->path('cms.helpers')[0];

		foreach (Site::all() as $module) {
			$helperNs = $module['inflected_namespace'] . '.helpers';
			$paths[$helperNs] = Loader::instance()->path($helperNs)[0];
		}

		$helpers	= array();\Speedy\Logger::debug($paths);
		foreach ($paths as $ns => $path) {\Speedy\Logger::debug($ns);
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
		}\Speedy\Logger::debug($helpers);
		
		return $helpers;
	} 
	
}
?>