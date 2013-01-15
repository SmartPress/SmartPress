<?php

use \ActiveRecord\Migration;

class Config extends Migration {

	public function change() {
		$this->create_table("configs", function() {
			$this->string("name");
			$this->text("value");
		});
	}
	
	public function up() {
		$this->query('CREATE UNIQUE INDEX name_index ON configs (name)');
	}

}

?>
