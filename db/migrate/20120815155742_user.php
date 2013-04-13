<?php

use \ActiveRecord\Migration;

class User extends Migration {

	public function change() {
		$this->create_table("users", function() {
			$this->string("username");
			$this->string("email");
			$this->string("password_hash");
			$this->integer("permissions");

			$this->timestamps();
		});
	}

}

?>
