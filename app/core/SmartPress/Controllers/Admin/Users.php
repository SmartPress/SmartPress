<?php
namespace SmartPress\Controllers\Admin;

use \SmartPress\Controllers\Admin\Admin;
use \SmartPress\Models\User;

class Users extends Admin {

	const APIGenerationSuccess = "Api successfully generated for user.";

	const APIGenerationFail	= "Error occured while generating api for user.";
	
	protected $minReadPrivilege	= SuperAdminPrivilege;
	
	protected $minWritePrivilege= SuperAdminPrivilege;
	

		/**
	 * GET /posts
	 */
	public function index() {
		$this->users	= User::all();
		
		$this->respondTo(function(&$format) {
			$format->html; // Render per usual
			$format->json	= function() {
				$this->render(array( 'json' => $this->users ));
			};
		});
	}

	/**
	 * GET /posts/1
	 */
	public function show() {
		$this->user	= User::find($this->params('id'));
		
		$this->respondTo(function(&$format) {
			$format->html; // show.php.html
			$format->json	= function() {
				$this->render(array( 'json' => $this->user ));
			};
		});
	}

	/**
	 * GET /posts/new
	 */
	public function _new() {
		$this->user	= new User();
		
		$this->respondTo(function($format) {
			$format->html; // new.php.html
			$format->json = function() {
				$this->render(array( 'json' => $this->user ));
			};
		});
	}

	/**
	 * GET /posts/1/edit
	 */
	public function edit() {
		$this->user	= User::find($this->params('id'));
	}

	/**
	 * POST /posts
	 */
	public function create() {
		$this->user	= new User($this->params('user'));
		
		$this->respondTo(function($format) {
			if ($this->user->save()) {
				$format->html = function() {
					$this->redirectTo($this->edit_admin_user_path($this->user), array("notice" => "User was successfully created."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->user ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->user->errors ));
				};
			}
		});
	}

	/**
	 * PUT /posts/1
	 */
	public function update() {
		$this->user	= User::find($this->params('id'));
		$attrs = $this->params('user');
		if (empty($attrs['password'])) {
			unset($attrs['password']);
			unset($attrs['password_confirm']);
		}
		$this->attrs = $attrs;
		
		$this->respondTo(function($format) {
			if ($this->user->update_attributes($this->attrs)) {
				$format->html = function() {
					$this->redirectTo($this->edit_admin_user_path($this->user), array("notice" => "User was successfully updated."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->user ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->user->errors ));
				};
			}
		});
	}

	/** 
	 * DELETE /posts/1
	 */
	public function destroy() {
		$this->user = User::find($this->params('id'));
		$this->user->destroy();
		
		$this->respondTo(function($format) {
			$format->html = function() { $this->redirectTo($this->admin_users_url()); };
		});
	}

	/**
	 * GET /admin/users/1/generateApi
	 */
	public function generateApi() {
		$this->user = User::find($this->params('id'));

		$this->respondTo(function($format) {
			if ($this->user->generateApi()->save()) {
				$format->html = function() {
					$this->redirectTo($this->edit_admin_user_path($this->user), ['success' => self::APIGenerationSuccess]);
				};
				$format->json = function() {
					$this->render([
							'json' => [
								'success'	=> true,
								'message'	=> self::APIGenerationSuccess
							]
						]);
				};
			} else {
				$format->html = function() {
					$this->redirectTo($this->edit_admin_user_path($this->user), ['error' => self::APIGenerationFail]);
				};
				$format->json = function() {
					$this->render([
							'json' => [
								'success' 	=> false,
								'message'	=> self::APIGenerationFail
							]
						]);
				};
			}
		});
	}

}

?>
