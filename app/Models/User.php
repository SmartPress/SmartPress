<?php
namespace SmartPress\Models;


use Speedy\Cache;
use Speedy\Request;
use SmartPress\Mailers\UserMailer;

class User extends \Speedy\Model\ActiveRecord {
	
	private $_password;

	private $_sendWelcome = false;
	
	public $password_confirm;
	
	static $validates_confirmation_of = ['password'];
	static $validates_size_of = [
		['password', 'within' => [6, 16]]
	];
	
	static $validates_uniqueness_of = ['email'];

	static $after_save = ['clearCache'];

	static $after_create = ['sendWelcomeEmail'];

	static $after_update = ['sendWelcomeEmail'];

	static $after_destroy = ['clearCache'];

	const AllOptionsCacheName = 'users_options';
	

	public function clearCache() {
		Cache::clear(self::AllOptionsCacheName);
	}

	public function sendWelcomeEmail() {
		if (!$this->_sendWelcome)
			return;

		UserMailer::newUserWelcome($this, $this->password);
	}
	
	public function set_password($password) {
		$this->assign_attribute('password_hash', $this->crypto($password));
		$this->_password = $password;
	}

	public function get_password() {
		return $this->_password;
	}

	public function set_send_welcome($welcome) {
		$this->_sendWelcome = true;
	}

	public function get_send_welcome() {
		return $this->_sendWelcome;
	}

	public function get_generate_password() {
		return null;
	}

	public function set_generate_password($gen) {
		$this->password = $this->generatePassword();
		$this->password_confirm = $this->password;
	}

	public function generatePassword() {
		return substr(self::crypto(time() . ':' . Request::instance()->host()), 2, 8);
	}
	
	public function crypto($rawPassword) {
		return md5($rawPassword);
	}

	public function passwordMatch($testPassword) {
		return ($this->password_hash == $this->crypto($testPassword)) ? true : false;
	}

	public static function allForOptions() {
		$options = Cache::read(self::AllOptionsCacheName);
		if (empty($options)) {
			$all = self::all();
			$options = ['' => 'Select One'];
			foreach ($all as $user) {
				$options[$user->id] = (isset($user->name)) ? $user->name : $user->email;
			}

			Cache::write(self::AllOptionsCacheName, $options);
		}

		return $options;
	}
}

?>
