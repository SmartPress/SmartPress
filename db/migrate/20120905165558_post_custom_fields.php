<?php

use \Speedy\ActiveRecord\Migration;

class PostCustomFields extends Migration {

	public function change() {
		$this->create_table("post_custom_fields", function() {
			$this->string("field");
			$this->string("label");

			$this->timestamps();
		});
	}

}

?>