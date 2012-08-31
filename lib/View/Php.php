<?php 
namespace Cms\Lib\View;

use \Speedy\Loader;

class Php extends \Speedy\View\Php {
	
	protected function __loadMixins() {
		$helperPaths	= Loader::instance()->path('helpers');
		// $helperPaths	= implode(',', $helperPaths);
		
		//$files	= rglob('*.php', GLOB_BRACE, '{' . $helperPaths . '}'); 
		//debug($files);
		$helpers	= array();
		foreach ($helperPaths as $path) {
			$files	= rglob('*.php', 0, $path);

			foreach ($files as $file) {
				$info	= pathinfo($file);
				$dir	= $info['dirname'];
				
				$import	= "cms.helpers";
				if (preg_match("#{$path}/(.*)#", $dir, $matches)) {
					$import	.= '.' . str_replace('/', '.', $matches[1]);
				} 
				
				$import .= '.' . strtolower($info['filename']);
				$helpers[]	= $import;
			}
		}
		
		return $helpers;
	} 
	
}
?>