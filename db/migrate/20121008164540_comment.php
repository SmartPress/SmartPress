<?php

use \ActiveRecord\Migration;

class Comment extends Migration {

	public function change() {
		$this->create_table("comments", function() {
			$this->integer("post_id");
			$this->integer("user_id");
			$this->text("content");
			$this->string("author");
			$this->string("author_email");
			$this->string("author_url");
			$this->string("author_ip");
			$this->integer("status");

			$this->timestamps();
		});
	}

}

?>