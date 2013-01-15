<?php

use \ActiveRecord\Migration;

class Upload extends Migration {

	public function change() {
		$this->create_table("uploads", function() {
			$this->string("filename");
			$this->string("type");
			$this->string("size");

			$this->timestamps();
		});
	}

}

?>
