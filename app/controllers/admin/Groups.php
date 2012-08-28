<?php
namespace Cms\Controllers\Admin;

use \Cms\Controllers\Admin\Admin;
use \Cms\Models\Group;

class Groups extends Admin {

		/**
	 * GET /posts
	 */
	public function index() {
		$this->groups	= Group::all();
		
		$this->respondTo(function(&$format) {
			$format->html; // Render per usual
			$format->json	= function() {
				$this->render(array( 'json' => $this->groups ));
			};
		});
	}

	/**
	 * GET /posts/1
	 */
	public function show() {
		$this->group	= Group::find($this->params('id'));
		
		$this->respondTo(function(&$format) {
			$format->html; // show.php.html
			$format->json	= function() {
				$this->render(array( 'json' => $this->group ));
			};
		});
	}

	/**
	 * GET /posts/new
	 */
	public function _new() {
		$this->group	= new Group();
		
		$this->respondTo(function($format) {
			$format->html; // new.php.html
			$format->json = function() {
				$this->render(array( 'json' => $this->group ));
			};
		});
	}

	/**
	 * GET /posts/1/edit
	 */
	public function edit() {
		$this->group	= Group::find($this->params('id'));
	}

	/**
	 * POST /posts
	 */
	public function create() {
		$this->group	= new Group($this->params('group'));
		
		$this->respondTo(function($format) {
			if ($this->group->save()) {
				$format->html = function() {
					$this->redirectTo($this->admin_groups_url(), array("notice" => "Group was successfully created."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->group ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->group->errors ));
				};
			}
		});
	}

	/**
	 * PUT /posts/1
	 */
	public function update() {
		$this->group	= Group::find($this->params('id'));
		
		$this->respondTo(function($format) {
			if ($this->group->update_attributes($this->params('group'))) {
				$format->html = function() {
					$this->redirectTo($this->admin_groups_url(), array("notice" => "Group was successfully updated."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->group ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->group->errors ));
				};
			}
		});
	}

	/** 
	 * DELETE /posts/1
	 */
	public function destroy() {
		$this->group = Group::find($this->params('id'));
		$this->group->destroy();
		
		$this->respondTo(function($format) {
			$format->html = function() { $this->redirectTo($this->admin_groups_url()); };
		});
	}

}

?>