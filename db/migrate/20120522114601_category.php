<?php

use \Speedy\ActiveRecord\Migration;

class Category extends Migration {

	public function change() {
		$this->create_table("categories", function() {
			$this->integer("parent_id");
			$this->integer("lft");
			$this->integer("rght");
			$this->string("name");
			$this->string("slug");
		});
	}

}

?>