<?php

use \ActiveRecord\Migration;

class User extends Migration {

	public function change() {
		$this->create_table("users", function() {
			$this->string("email");
			$this->string("password_hash");
			$this->integer("permissions");
			$this->string("name");
			$this->string("api_id");
			$this->string("api_key");
			$this->integer('api_generated_at');

			$this->timestamps();
		});
	}

}

?>
