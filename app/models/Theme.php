<?php 
namespace Cms\Models;


use \Speedy\Singleton;
use \Speedy\Cache;

class Theme extends Singleton
{
	
	private $_themes;
	
	
	
	public static function all() {
		return self::instance()->findAll();
	}
	
	public function findAll() {
		if ($this->_themes) return $this->_themes;
		// Get from cache if possible otherwise build and save to cache
		$themes	= Cache::read('themes');
		if (empty($themes)) {
			// Loop the directory
			$directory = ROOT . DS . 'themes';
			
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
				Cache::write('themes', $themes);
			}
		}
		$this->_themes = $themes;
		
		// return the results
		return $this->_themes;
	}
}

?>