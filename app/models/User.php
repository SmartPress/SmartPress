<?php
namespace Cms\Models;

use \Speedy\Model\ActiveRecord\Base;

class User extends Base {
	
	//public $password;
	
	public $password_confirm;
	
	static $validates_confirmation_of = ['password'];
	static $validates_size_of = [
		['password', 'within' => [6, 16]]
	];
	
	static $validates_uniqueness_of = ['username'];
	
	static $belongs_to = [
		['group']
	];
	
	
	public function set_password($password) {
		$this->password = $password;
		$this->assign_attribute('password', md5($password));
	}
}

?>