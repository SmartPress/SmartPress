<?php

use \Speedy\ActiveRecord\Migration;

class Config extends Migration {

	public function change() {
		$this->create_table("configs", function() {
			$this->string("name");
			$this->text("value");
		});
	}

}

?>