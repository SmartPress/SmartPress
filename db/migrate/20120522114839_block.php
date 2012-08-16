<?php

use \Speedy\ActiveRecord\Migration;

class Block extends Migration {

	public function change() {
		$this->create_table("blocks", function() {
			$this->string("path");
			$this->string("block");
			$this->string("element");
			$this->text("params");
			$this->integer("priority");
		});
	}

}

?>