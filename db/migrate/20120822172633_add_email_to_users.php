<?php

use \Speedy\ActiveRecord\Migration;

class AddEmailToUsers extends Migration {

	public function change() {
		$this->add_column('users', 'email', 'string');
	}

}

?>