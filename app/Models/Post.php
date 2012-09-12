<?php
namespace Cms\Models;


use \Speedy\Model\ActiveRecord\Base;

import("cms.lib.markdown");

class Post extends Base {
	
	
	private static $_statuses = ['Select One', 'Draft', 'Publish'];
	
	private $_custom_data;
	
	
	
	public function __construct(array $attributes=array(), $guard_attributes=true, $instantiating_via_find=false, $new_record=true) {
		return parent::__construct($attributes, $guard_attributes, $instantiating_via_find, $new_record);
	}
	
	public function set_custom_data($data) {
		$this->assign_attribute('custom_data', serialize($data));
	}
	
	public function get_custom_data() {
		if (!$this->_custom_data) {
			$this->_custom_data = unserialize($this->read_attribute('custom_data'));
		}
		
		return $this->_custom_data;
	}
	
	public function get_status_label() {
		$status	= $this->status;
		return self::$_statuses[$status];
	}
	
	public static function statuses() {
		return self::$_statuses;
	}
	
	public function get_html() {
		return \Markdown($this->content);
	}
	
}

?>
