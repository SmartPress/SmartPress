<?php
namespace SmartPress\Mailers;


use Speedy\Request;

class UserMailer extends \Speedy\Mailer {

	protected $default	= ['from' => 'donotreply@careplacement.com'];


	public static function newUserWelcome($user, $password) {
		$data = [
			'user' => $user, 
			'password' => $password, 
			'domain' => Request::instance()->host()
		];
		\Speedy\Logger::debug($user->email);
		return self::mail([
				'to' => $user->email,
				'from'	=> 'donotreply@' . $data['domain'],
				'subject' => 'Welcome to ' . $data['domain'] . ', from the SmartPress Administration'
			], 
			$data);
	}

}
?>