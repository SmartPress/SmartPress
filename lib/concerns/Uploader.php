<?php 
namespace Cms\Lib\Concerns;

use \Cms\Lib\Utility\Uploader as UploadUtility;
use \Cms\Lib\Config\FileUpload as Settings;

class Uploader extends \Speedy\Object {
	
	/**
	* Uploader is the uploader instance of class \Cms\Lib\Utility\Uploader. This will handle the actual file saving.
	* @var object
	*/
	public $Uploader;
	
	/**
	 * Parent object
	 * @var object
	 */
	public $Model;
	
	/**
	 * Options array
	 * @var array
	 */
	public $options = array();
	
	public $before_save = array('uploadFile');
	
	public $before_validation = array('validateUpload');
	
	public $before_destroy	= array('deleteFile');
	
	
	
	
	public function __construct(&$parent, $options) {
		$this->Model =& $parent;
		$FileUploadSettings = new Settings;
		
		if(!is_array($options)) $options = array();
		$this->options = array_merge($FileUploadSettings->defaults, $options);
		
		$uploader_settings = $this->options;
		$uploader_settings['uploadDir'] = $this->options['forceWebroot'] ? WWW_ROOT . $uploader_settings['uploadDir'] : $uploader_settings['uploadDir'];
		$uploader_settings['fileModel'] = $this->Model;
		$this->Uploader = new UploadUtility($uploader_settings);
	}
	
	function setFileUploadOption($key, $value) {
		$this->options[$key] = $value;
		$this->setOption($key, $value);
	}
	
	/**
	 * Setup the behavior
	 *
	function setUp(&$Model, $options = array()){
		$FileUploadSettings = new Settings;
		if(!is_array($options)) $options = array();
		$this->options[$Model->alias] = array_merge($FileUploadSettings->defaults, $options);
	
		$uploader_settings = $this->options[$Model->alias];
		$uploader_settings['uploadDir'] = $this->options[$Model->alias]['forceWebroot'] ? WWW_ROOT . $uploader_settings['uploadDir'] : $uploader_settings['uploadDir'];
		$uploader_settings['fileModel'] = $Model->alias;
		$this->Uploader[$Model->alias] = new Uploader($uploader_settings);
	}*/
	
	/**
	 * beforeSave if a file is found, upload it, and then save the filename according to the settings
	 *
	 */
	function uploadFile() {
		if (isset($this->Model->attributes[$this->options['fileVar']])) {
			$file = $this->Model->attributes[$this->options['fileVar']];
			$this->Uploader->file = $file;
	
			if($this->Uploader->hasUpload()){
				$fileName = $this->Uploader->processFile();
	
				if ($fileName) {
					$this->Model->attributes[$this->options['fields']['name']] = $fileName;
					$this->Model->attributes[$this->options['fields']['size']] = $file['size'];
					$this->Model->attributes[$this->options['fields']['type']] = $file['type'];
				} else {
					return false; // we couldn't save the file, return false
				}
	
				unset($this->Model->attributes[$this->options['fileVar']]);
			} else {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Updates validation errors if there was an error uploading the file.
	 * presents the user the errors.
	 */
	function validateUpload(){
		if (isset($this->Model->attributes[$this->options['fileVar']])) {
			$file = $this->Model->attributes[$this->options['fileVar']];
			$this->Uploader->file = $file;
				
			if ($this->Uploader->hasUpload()) {
				if (!$this->Uploader->checkFile() 
					|| !$this->Uploader->checkType() 
					|| !$this->Uploader->checkSize()) {
					$this->Model->errors->add($this->options['fileVar'], $this->Uploader->showErrors());
					return false;
				}
			} else {
				if (isset($this->options['required']) && $this->options['required']) {
					$this->Model->errors->add($this->options['fileVar'], 'Select file to upload');
					return false;
				}
			}
		} elseif (isset($this->options['required']) && $this->options['required']) {
			$this->Model->errors->add($this->options['fileVar'], 'No File');
			return false;
		}
	
		return true;
	}
	
	/**
	 * Automatically remove the uploaded file.
	 */
	function deleteFile($cascade){
		return $this->Uploader->removeFile($this->Model->attributes[$this->options['fields']['name']]);
	}
	
}
?>