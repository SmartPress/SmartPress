<?php
namespace Cms\Models;


class Upload extends \Speedy\Model\ActiveRecord {
	
	static $before_destroy	= ['deleteFile'];
	
	private $_info;
	
	private $_imageTypes = [
		'image/jpeg',
		'image/jpg',
		'image/gif',
		'image/png'
	];
	
	
	public function deleteFile() {
		return (@unlink(PUBLIC_UPLOADS_DIR . DS . $this->filename)) ? true : false;
	}
	
	public function get_info() {
		if (!$this->_info && $this->isImage()) {
			$this->_info = [];
			list($this->_info['width'], $this->_info['height']) = getimagesize(PUBLIC_UPLOADS_DIR . DS . $this->filename);
		}
		
		return $this->_info;
	}
	
	public function get_width() {
		return ($this->isImage() && isset($this->info['width'])) ? $this->info['width'] : null;
	}
	
	public function get_height() {
		return ($this->isImage() && isset($this->info['height'])) ? $this->info['height'] : null;
	}
	
	public function isImage() {
		return in_array($this->type, $this->_imageTypes);
	}
	
}

?>