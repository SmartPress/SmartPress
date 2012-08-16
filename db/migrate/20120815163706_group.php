<?php

use \Speedy\ActiveRecord\Migration;

class Group extends Migration {

	public function change() {
		$this->create_table("groups", function() {
			$this->string("name");
			$this->integer("read_privileges");
			$this->integer("write_privileges");
		});
	}

}

?>