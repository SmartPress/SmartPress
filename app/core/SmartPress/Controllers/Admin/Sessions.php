<?php
namespace SmartPress\Controllers\Admin;

use SmartPress\Controllers\Application;
use SmartPress\Models\User;
use Speedy\Session;

class Sessions extends Application {

	const NoUserErrorMsg = 'Unable to find the username or email you provided';
	const PasswordMismatchErrorMsg = 'Password doesn\'t match';
	
	public $layout = "admin/signin";
	
	
	
	public function index() {
		if ($this->user()) {
			$this->redirectTo($this->admin_users_url());
		}
	}
	
	public function _new() {
	}
			
	public function create() {		
		$user = User::find_by_email($this->params('email'));
		
		if (!$user) {
			Session::instance()->write('flash.error', self::NoUserErrorMsg);
			$this->render('new');
			return;
		}
		
		if ($user->passwordMatch($this->params('password'))) {
			Session::instance()->write('User', base64_encode(serialize($user)));
			$this->redirectTo($this->admin_users_url());
		} else {
			Session::instance()->write('flash.error', self::PasswordMismatchErrorMsg);
			$this->render('new');
		}
	}
			
	public function destroy() {
		Session::instance()->delete('User');

		$this->respondTo(function(&$format) {
			$format->html = function() {
				$this->render('new');
			};
			$format->json = function() {
				$this->render(['json' => ['success' => true]]);
			};
		});
	}

	public function authenticate() {
		
	}
		
}

?>
