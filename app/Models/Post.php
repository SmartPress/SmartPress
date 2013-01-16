<?php
namespace SmartPress\Models;



import("smart_press.lib.markdown");

class Post extends \Speedy\Model\ActiveRecord {
	
	use \SmartPress\Lib\Concerns\Pagination;
	
	const PageType	= 'page';
	
	const PostType	= 'post';
	
	private static $_statuses = ['Select One', 'Draft', 'Publish'];

	public static $homeTypes = [
		'Select One',
		'Blog Roll',
		'Single Page'
	];

	const BlogRollHomeType = 1;

	const SinglePageHomeType = 2;
	
	private $_custom_data;
	
	static $has_many = [
		['comments', 'order' => 'created_at ASC', 'conditions' => ['status = ?', Comment::ApprovedStatus]]
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
