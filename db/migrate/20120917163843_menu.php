<?php

use \Speedy\ActiveRecord\Migration;

class Menu extends Migration {

	public function change() {
		$this->create_table("menus", function() {
			$this->integer("parent_id");
			$this->integer("lft");
			$this->integer("rght");
			$this->string("title");
			$this->string("url");
		});
	}

}

?>