<?php
namespace Cms\Models;



import("cms.lib.markdown");

class Post extends \Speedy\Model\ActiveRecord {
	
	const PageType	= 'page';
	
	const PostType	= 'post';
	
	private static $_statuses = ['Select One', 'Draft', 'Publish'];
	
	private $_custom_data;
	
	static $has_many = [
		['comments', 'order' => 'created_at ASC']
	];
	
	
	
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
	
	public function get_summary() {
		return strip_tags($this->html);
	}
	
	public static function allPages() {
		return self::where(['type' => self::PageType]);
	}
	
}

?>
