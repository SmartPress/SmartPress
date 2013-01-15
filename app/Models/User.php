<?php
namespace SmartPress\Models;



class User extends \Speedy\Model\ActiveRecord {
	
	//public $password;
	
	public $password_confirm;
	
	static $validates_confirmation_of = ['password'];
	static $validates_size_of = [
		['password', 'within' => [6, 16]]
	];
	
	static $validates_uniqueness_of = ['username'];
	
	static $belongs_to = [
		['group', 'namespace' => '\\SmartPress\\Models']
	];
	
	
	public function set_password($password) {
		//output("PASSWORD: " . $password);
		//$this->password = $password;
		$this->assign_attribute('password', $this->crypto($password));
	}
	
	public function crypto($rawPassword) {
		return md5($rawPassword);
	}

	public function passwordMatch($testPassword) {
		return ($this->password_hash == $this->crypto($testPassword)) ? true : false;
	}
}

?>
