<?php
namespace SmartPress\Models;



class Category extends \Speedy\Model\ActiveRecord {

	use \SmartPress\Lib\Concerns\Tree;

	static $has_many = [
		['post_categories'],
		['posts', 'through' => 'post_categories']
	];


	public static function options() {
		$options = self::tree('-', ['title' => 'name']);
		$options->prepend(new Category(['name' => 'Select One']));
		return $options;
	}
}

?>
