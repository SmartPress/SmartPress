<?php

use \ActiveRecord\Migration;

class Group extends Migration {

	public function change() {
		$this->create_table("groups", function() {
			$this->string("name");
			$this->integer("privileges");
		});
	}
	
	public function up() {
		$superAdmin = new \Cms\Models\Group(['name' => 'Super Admin', 'privileges' => 255]);
		$superAdmin->save();
		
		$admin	= new \Cms\Models\Group(['name' => 'Admin', 'privileges' => 16]);
		$admin->save();
	}

}

?>