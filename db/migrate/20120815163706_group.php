<?php

use \ActiveRecord\Migration;

class Group extends Migration {

	public function change() {
		$this->create_table("groups", function() {
			$this->string("name");
			$this->integer("privilege");
		});
	}
	
	public function up() {
		$superAdmin = new \SmartPress\Models\Group(['name' => 'Super Admin', 'privileges' => 255, 'id' => 1]);
		$superAdmin->save();
		
		$admin	= new \SmartPress\Models\Group(['name' => 'Admin', 'privileges' => 16, 'id' => 2]);
		$admin->save();
	}

}

?>
