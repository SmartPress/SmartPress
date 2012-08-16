<?php

use \Speedy\ActiveRecord\Migration;

class Post extends Migration {

	public function change() {
		$this->create_table("posts", function() {
			$this->string("title");
			$this->text("content");
			$this->text("custom_data");
			$this->string("type");
			$this->string("slug");
			$this->string("layout");
			$this->integer("status");

			$this->timestamps();
		});
	}

}

?>