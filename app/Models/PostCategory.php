<?php
namespace SmartPress\Models;

use \Speedy\Model\ActiveRecord;

class PostCategory extends ActiveRecord {

	static $belongs_to = [
		['post'],
		['category']
	];

}

?>