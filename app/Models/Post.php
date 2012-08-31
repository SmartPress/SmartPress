<?php
namespace Cms\Models;


use \Speedy\Model\ActiveRecord\Base;

class Post extends Base {
	
	
	private static $_statuses = ['Select One', 'Draft', 'Publish'];
	
	
	
	public static function statuses() {
		return self::$_statuses;
	}
	
}

?>