<?php
namespace Cms\Models;


use \Speedy\Model\ActiveRecord\Base;

import("cms.lib.markdown");

class Post extends Base {
	
	
	private static $_statuses = ['Select One', 'Draft', 'Publish'];
	
	
	
	public function __construct(array $attributes=array(), $guard_attributes=true, $instantiating_via_find=false, $new_record=true) {
		return parent::__construct($attributes, $guard_attributes, $instantiating_via_find, $new_record);
	}
	
	public function set_custom_data($data) {
		$this->assign_attribute('custom_data', serialize($data));
	}
	
	public function get_custom_data() {
		return unserialize($this->read_attribute('custom_data'));
	}
	
	public function get_status_label() {
		$status	= $this->status;
		return self::$_statuses[$status];
	}
	
	public static function statuses() {
		return self::$_statuses;
	}
	
	public function serializeCustomData() {
		output('Attempting to serialize data');
		debug($this->custom_data);
		if (!isset($this->custom_data)) return;
		if (!is_array($this->custom_data)) return;
		
		$this->custom_data = serialize($this->custom_data);
	}
	
	public function get_html() {
		return \Markdown($this->content);
	}
	
}

?>