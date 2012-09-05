<?php 
namespace Cms\Models;


use \Speedy\Cache;
use \Speedy\Singleton;
use \Speedy\Utility\File;
use \Cms\Lib\Exceptions\Theme as ThemeException;


define('THEME_DIR', ROOT . DS . 'themes');
define('PUBLIC_RESOURCE_DIR', PUBLIC_DIR . DS . 'resources');

class Theme extends Singleton {
	
	const CacheName = 'themes';
	
	const DIR = THEME_DIR;
	
	const ResourceDir = PUBLIC_RESOURCE_DIR;
	
	private $_error;
	
	private $_themes;
	
	
	
	/**
	 * Installer for theme
	 * @param string filename
	 * @throws \Cms\Exceptions\Theme
	 */
	public static function install($zip) {
		$arch = new ZipArchive();
		if ($arch->open($zip) === false) {
			throw new ThemeException("Unable to open theme archive.");
		}
		
		if (!$arch->locateName('theme.xml')) {
			throw new ThemeException("Missing required manifest \"theme.xml\" in archive");
		}
		
		if (!$arch->locateName('views') || !$arch->locateName('resources')) {
			throw new ThemeException("Missing required views or resources directory");
		}
		
		$info = pathinfo($zip);
		$arch->extractTo(self::DIR . DS . $info['filename']);
	}
	
	public static function delete($themeDir) {
		return File::rm_rf($themeDir);
	}
	
	public static function all() {
		return self::instance()->findAll();
	}
	
	public static function updateTheme($config) {
		$resources = $config->value . DS . 'resources';
		File::cp_r($resources, self::ResourceDir);
	}
	
	public function findAll() {
		if ($this->_themes) return $this->_themes;
		// Get from cache if possible otherwise build and save to cache
		$themes	= Cache::read(self::CacheName);
		if (empty($themes)) {
			// Loop the directory
			$directory = self::DIR;
			
			$themes	= [];
			foreach (glob($directory . DS . '*' . DS . 'theme.xml') as $manifest) {
				$xml	= @simplexml_load_file($manifest); 
				// Gather pertinent details into array
				// Need name, author, version, snapshot
				if (!$xml) {
					continue;
				}
				
				if (!isset($xml->name) 
						|| !isset($xml->author) 
						|| !isset($xml->version)) {
					continue;
				}
				$details	= [
					'name'	=> (string) $xml->name,
					'author'=> (string) $xml->author,
					'version'	=> (string) $xml->version,
					'snapshot'	=> (string) $xml->snapshot,
					'path'	=> dirname($manifest)
				];
				$themes[]	= $details;
			}
			
			// Cache the results
			if (count($themes) > 0) {
				Cache::write(self::CacheName, $themes);
			}
		}
		$this->_themes = $themes;
		
		// return the results
		return $this->_themes;
	}
}

?>