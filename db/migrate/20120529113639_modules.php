<?php

use \ActiveRecord\Migration;

class Modules extends Migration {

	public function change() {
		$this->create_table("modules", function() {
			$this->string("name");
			$this->string("code");
			$this->string("version");
			$this->integer("status", 3);
		});
	}

}

?>