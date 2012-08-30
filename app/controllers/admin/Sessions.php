<?php
namespace Cms\Controllers\Admin;

use \Cms\Controllers\Application;
use \Cms\Models\User;
use \Speedy\Session;

class Sessions extends Application {

	const NoUserErrorMsg = 'Unable to find the username or email you provided';
	const PasswordMismatchErrorMsg = 'Password doesn\'t match';
	
	public $layout = "admin/signin";
	
	
	
	public function _new() {
	}
			
	public function create() {		
		$user = User::find_by_email_or_username($this->params('username'));
		
		if (!$user) {
			Session::instance()->write('flash.error', self::NoUserErrorMsg);
			$this->render('new');
			return;
		}
		
		if ($user->passwordMatch($this->params('password'))) {
			Session::instance()->write('User.id', $user->id);
			$this->redirectTo($this->admin_users_url());
		} else {
			Session::instance()->write('flash.error', self::PasswordMismatchErrorMsg);
			$this->render('new');
		}
	}
			
	public function destroy() {
	}
		
}

?>