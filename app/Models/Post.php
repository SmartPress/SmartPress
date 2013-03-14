<?php
namespace SmartPress\Models;



use Speedy\Cache;
use PLinq\PLinq;

import("smart_press.lib.markdown");
define('POST_CACHE_FORMAT', '%1' . DS . "%2");

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

	const NoneSelectedStatus = 0;

	const DraftStatus = 1;

	const PublishStatus = 2;

	const BlogRollHomeType = 1;

	const SinglePageHomeType = 2;
	
	private $_custom_data;
	
	static $has_many = [
		['comments', 'order' => 'created_at ASC', 'conditions' => ['status = ?', Comment::ApprovedStatus]],
		['post_categories'],
		['categories', 'through' => 'post_categories']
	];

	static $belongs_to = [
		['author', 'class_name' => 'User', 'foreign_key' => 'author_id']
	];

	const CacheFormat = POST_CACHE_FORMAT;

	public static $after_save = ['clearCaches'];
	public static $after_update = ['clearCaches', 'clearCategories'];
	public static $before_destroy = ['clearCaches'];

	
	
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
		return $this->html;
	}
	
	public static function allPages() {
		return self::where(['type' => self::PageType]);
	}

	public static function find_by_slug_or_id($slugOrId, $type) {
		//$post = Cache::read(self::singleCache($slugOrId, $type));
		//if (empty($post)) {
			$post = self::first([
				'conditions'=> ['(slug = ? OR id = ?) AND type = ?', $slugOrId, $slugOrId, $type]
			]);
		//	Cache::write(self::singleCache($slugOrId, $type), $post);
		//}

		return $post;
	}
	
	public function clearCaches() {
		Cache::clear($this->singleCache(
				(isset($this->slug)) ? $this->slug : $this->id, $this->type));
		Cache::clear('page_slugs');
	}

	public function clearCategories() {
		PostCategory::table()->delete(['post_id' => $this->id]);
	}

	public function findCategory($cat_id) {
		$cats = ($this->categories) ? $this->categories : [];
		return PLinq::from($cats)
					->firstOrDefault(function($cat) use ($cat_id) {
						return $cat->id == $cat_id;
					});
	}

	private static function singleCache($slug, $type) {
		$cacheName = str_replace('%1', $type, self::CacheFormat);
		$cacheName = str_replace('%2', $slug, $cacheName);
		return $cacheName;
	}

}

?>
