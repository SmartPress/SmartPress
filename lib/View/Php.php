<?php 
namespace SmartPress\Lib\View;


use Speedy\Loader;
use Speedy\Utility\Inflector;
use SmartPress\Lib\Module\Site;

class Php extends \Speedy\View\Php {
	
	protected function __loadMixins() {
		// 2-dimensional array of paths
		$aPaths['smart_press.helpers'] = Loader::instance()->path('smart_press.helpers');

		foreach (Site::all() as $module) {
			$helperNs = $module['inflected_namespace'] . '.helpers';
			$aPaths[$helperNs] = Loader::instance()->path($helperNs);
		}

		$helpers	= array();
		foreach ($aPaths as $ns => $paths) {
			if (!is_array($paths)) 
				$paths = [$paths];

			$helpers = array_merge($helpers, $this->gatherImports($ns, $paths));
		}
		
		return $helpers;
	} 

	/**
	 * Gather imports
	 * 
	 * @param string $paths
	 * @return array
	 */
	private function gatherImports($ns, $paths) {
		$helpers = [];
		foreach ($paths as $path) {
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
