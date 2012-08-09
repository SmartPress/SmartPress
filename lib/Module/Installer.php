<?php 
namespace Cms\Lib\Module;


use \Speedy\Cache;
use \Speedy\Singleton;
use \Speedy\Loader;
use \Speedy\Utility\Inflector;
use \Cms\Lib\Module\Exception as MException;
use \Cms\Models\Module;
use \ZipArchive;

class Installer extends Singleton {

	/**
	 * String of zipFile
	 * @var string 
	 */
	public $zipFile;
	
	/**
	 * Database record
	 * @var object
	 */
	public $record;
	
	/**
	 * Error message
	 * @var string
	 */
	private $_error;
	
	/**
	 * Success message
	 * @var string
	 */
	private $_success;
	
	/**
	 * Module is processed
	 * @var boolean
	 */
	private $_processed = false;
	
	const NO_CODE_MATCH 	= 'Module codes do not match!';
	const NO_CONFIG_FILE 	= 'No configuration file found';
	const ARCHIVE_FAIL 		= 'Failed to open module install file';
	const UPDATE_SUCCESS 	= 'Module updated successfully';
	const UPDATE_FAIL 		= 'Module updated failed!';
	const INSTALL_SUCCESS 	= 'Module installed and updated successfully';
	const INSTALL_FAIL	 	= 'Module install failed!';
	const UNKNOWN_ERROR 	= "Unknown error";
	
	
	
	/**
	 * Attr getter for processed
	 * @return boolean
	 */
	public function processed() {
		return $this->_processed;
	}
	
	/**
	 * Atter getter for success message
	 * @return string
	 */
	public function success() {
		return $this->_success;
	}
	
	/**
	 * Attr getter for error
	 * @return string
	 */
	public function error() {
		return $this->_error;
	}
	
	/**
	 * Setter for zipFile attr
	 * @param string $zipFile
	 * @return object $this
	 */
	public function setZipFile($zipFile) {
		$this->zipFile	= $zipFile;
		return $this;
	}
	
	/**
	 * Attr getter for zipFile
	 * @throws \Cms\Lib\Module\Exception
	 * @return string $zipFile
	 */
	public function zipFile() {
		if (empty($this->zipFile)) {
			throw new MException('No file set');
		}
		return $this->zipFile;
	}
	
	/**
	 * Process zip file upload
	 * @param string optional $zip
	 * @return boolean
	 */
	public function processZip($zip = null) {
		if ($zip) $this->setZipFile($zip);
		else $zip	= $this->zipFile();
		$arch	= new ZipArchive(); 
	
		if (!$arch->open($zip)) {
			return $this->setProcessed(false, self::ARCHIVE_FAIL);
		}
		
		// Only work if arch has config manifest
		$xmlString = null;
		if (!($xmlString = $arch->getFromName('etc' . DS . 'config.xml'))) {
			$arch->close();
			return $this->setProcessed(false, self::NO_CONFIG_FILE);
		}
			
		if (!$xmlString) {
			$arch->close();
			return $this->setProcessed(false, self::UNKNOWN_ERROR);
		} 
			
		$xmlObj	= simplexml_load_string($xmlString);
		if (($module = Module::find_by_code((string) $xmlObj->code))) {
			/*if ($id && $id != $module->id) {
				$arch->close();
				return $this->setProcessed(false, self::NO_CODE_MATCH);
			}*/
				
			if ($this->update($module->id, $arch)) {
				Cache::clear("modules");
				$arch->close();
				return $this->setProcessed(true, self::UPDATE_SUCCESS);
			} else {
				$arch->close();
				return $this->setProcessed(false, self::UPDATE_FAIL);
			}
		} else {
			if ($this->_install($xmlObj, $arch)) {
				Cache::clear("modules");
				$arch->close();
				return $this->setProcessed(true, self::INSTALL_SUCCESS);
			} else {
				$arch->close();
				return $this->setProcessed(false, self::INSTALL_FAIL);
			}
		}
		
		$arch->close();
		return $this->setProcessed(false, self::UNKNOWN_ERROR);
	}
	
	/**
	 * Getter for record
	 * @return object
	 */
	public function record() {
		return $this->record;
	}
	
	/**
	 * Setter for record
	 * @param object $record
	 * @return object $this
	 */
	public function setRecord($record) {
		$this->record = $record;
		return $this;
	}
	
	/**
	 * Get the config for given module
	 * @param object $module
	 * @return object \SimpleXMLElement
	 */
	static function config($module) {
		$filename	= MODULES_PATH . DS . $module->code . DS . 'etc' . DS . 'config.xml';
		if (!file_exists($filename)) {
			throw new MException('Could not load config file at ' . $filename);
		}
		
		return simplexml_load_file($filename);
	}
	
	public function update($id, $archive = null) {
		$module	= Module::find($id);
	
		/*if ($archive && !$this->_unzipModule($archive, MODULES_PATH . $module['Module']['file_path'])) {
			return false;
		}*/
	
		if ($config = $module->config()) {		
			$settings	= $module->settings();
				
			if ($settings->update($module->version)) {
				$module->version = $config->version;
				if ($module->save()) {
					return true;
				} else {
					return $this->setProcessed(false, 'Error occured while trying to update, unabled to update the database. Please try again!');
				}
			} else {
				return $this->setProcessed(false, 'Error occured while trying to update <br />' . $settings->lastError());
			}
		}
	
		return $this->setProcessed(false, 'Unknown error occured');
	}
	
	/**
	 * Attr setter for error
	 * @param string $error
	 * @return object $this
	 */
	private function setError($error) {
		$this->_error = $error;
		return $this;
	}
	
	/**
	 * Attr setter for success
	 * @param string $success
	 * @return object $this
	 */
	private function setSuccess($success) {
		$this->_success = $success;
		return $this;
	}
	
	/**
	 * Attr setter for processed
	 * @param boolean $processed
	 * @return boolean $processed
	 */
	private function setProcessed($processed, $message = null) {
		if ($message && $processed) {
			$this->setSuccess($message);
		} elseif ($message && !$processed) {
			$this->setError($message);
		}
		
		$this->_processed = $processed;
		return $this->_processed;
	}
	
	private function _install($config, $archive) {
		if (!isset($config->name) || !isset($config->version) || !isset($config->code)) {
			$this->_error	= 'Manifest file missing required fields';
			return false;
		}
	
		if ($archive && !$folder = $this->_unzipModule($archive)) {
			return false;
		}
	
		$data = array();
		$data['name']	= $config->name;
		$data['status']	= 1;
		$data['version']= $config->version;
		$data['code']	= $config->code;
		//$data['file_path']	= (isset($folder)) ? $folder : $data['file_path'];
	
		//$pathToSettings	= (string) $data['file_path'] . ".etc." . Inflector::underscore($config->namespace) . "_settings";
		//import($pathToSettings);
		Loader::instance()->registerNamespace(Inflector::underscore($config->namespace), array(ROOT . DS . 'modules' . DS . $config->code));
		$class	= $config->namespace . '\\Etc\\Settings';
		
		$settings	= new $class($config);
		if (!$settings->install() && !$settings->update($data['version'])) {
			$this->_error	= 'Error occured while trying to update <br />' . $settings->lastError();
			return false;
		}
	
		$module = new Module($data);
		if ($module->save()) {
			$this->setRecord($module);
			return true;
		}
	
		$this->setError('Unknown error occured');
		return false;
	}
	
	private function _unzipModule($archive, $dest_path = null) {
		if (!$archive) return false;
	
		if (!$xmlString = $archive->getFromName('etc' . DS . 'config.xml')) {
			$this->_error	= 'Unable to create xml object of manifest file';
			return false;
		}
	
		$xmlObj	= simplexml_load_string($xmlString);
		$folder	= (!$dest_path) ? $xmlObj->code : $dest_path;
		$dest_path	= (!$dest_path) ? MODULES_PATH . DS . $xmlObj->code . DS : $dest_path . DS;
	
		if (!$this->_extractFolder($archive, 'etc', $dest_path)) {
			return false;
		}
	
		if (($index	= $archive->locateName(Inflector::underscore("{$xmlObj->namespace}_app_model.php"))) !== FALSE) {
			if (!$archive->extractTo($dest_path, $archive->getNameIndex($index))) {
				$this->_error	= 'Unable to extract module model';
				return false;
			}
		}
	
		if (($index	= $archive->locateName(Inflector::underscore("{$xmlObj->namespace}_app_controller.php"))) !== FALSE) {
			if (!$archive->extractTo($dest_path, $archive->getNameIndex($index))) {
				$this->_error	= 'Unable to extract module controller';
				return false;
			}
		}
	
		foreach ($xmlObj->features->feature as $feature) {
			switch($feature) {
				case 'controllers':
					if (!$this->_extractFolder($archive, 'controllers', $dest_path)) {
						return false;
					}
					break;
						
				case 'blocks':
					if (!$this->_extractFolder($archive, 'block', $dest_path)) {
						return false;
					}
					break;
						
				case 'models':
					if (!$this->_extractFolder($archive, 'models', $dest_path)) {
						return false;
					}
					break;
						
				case 'views':
					if (!$this->_extractFolder($archive, 'views', $dest_path)) {
						return false;
					}
					break;
			}
		}
	
		return $folder;
	}
	
	private function _extractFolder($zip, $folder, $dest_path) {
		for ($i = 0; $i < $zip->numFiles; $i++) {
			$filename	= $zip->getNameIndex($i);
			if ($filename[0] == '.') {
				continue;
			}
				
			if (strpos($filename, '.svn')) {
				continue;
			}
				
			$filenameArr	= explode(DS, $filename);
			if ($filenameArr[0] != $folder) {
				continue;
			}
				
			if (!$zip->extractTo($dest_path, $zip->getNameIndex($i))) {
				$this->_error = 'Failed to extract the ' . $folder . ' directory';
				return false;
			}
		}
	
		return true;
	}
}
?>