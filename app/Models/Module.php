<?php
namespace Cms\Models;


use \Speedy\Cache;
use \Cms\Lib\Module\Exception as MException;

defined('MODULE_UPLOAD_DIR') or define("MODULE_UPLOAD_DIR", ROOT . DS . 'tmp' . DS . 'uploads');

class Module extends \Speedy\Model\ActiveRecord {
	
	/*protected $_mixins = array(
		"\\Cms\\Lib\\Concerns\\Uploader" => array(
        	'uploadDir' => MODULE_UPLOAD_DIR,
          	'forceWebroot' => false, //if false, files will be upload to the exact path of uploadDir
          	//'fields' => array('name' => 'file_name', 'type' => 'file_type', 'size' => 'file_size'),
          	'allowedTypes' => array( 'zip' ),
          	'required' => true, //default is false, if true a validation error would occur if a file wsan't uploaded.
          	'maxFileSize' => '500000', //bytes OR false to turn off maxFileSize (default false)
          	'unique' => false, //filenames will overwrite existing files of the same name. (default true)
          	'fileNameFunction' => false //execute the Sha1 function on a filename before saving it (default false)
        )
	);*/
	
	private $_config;
	
	private $_filePath;
	
	static $after_destroy = [ 'clearCache' ];
	
	static $after_create = [ 'clearCache' ];
	
	
	
	public function clearCache() {
		Cache::clear('modules');
		Cache::clear('module_menus');
	}
	
	public static function findActives() {
		return self::all(array('conditions' => 'status = 1'));
	}
	
	public function config() {
		if (!$this->_config) {
			$filename	= $this->filePath() . DS . 'etc' . DS . 'config.xml';
			if (!file_exists($filename)) {
				throw new MException('Could not load config file at ' . $filename);
			}
			
			$this->setConfig(simplexml_load_file($filename));
		}
		
		return $this->_config;
	}
	
	/**
	 * Settings object
	 * @return object of Cms\Lib\Settings\Base
	 */
	public function settings() {
		$config	= $this->config();
		$class	= $config->namespace . '\\Etc\\Settings';
		return new $class($config);
	}
	
	public function filePath() {
		if (!isset($this->_filePath)) {
			$this->_filePath = MODULES_PATH . DS . $this->code;
		}
		
		return $this->_filePath;
	}
	
	private function setConfig($config) {
		$this->_config = $config;
		return $this;
	}
	
	public static function settingsFor($namespace) {
		
	}
	
}

?>