<?php
namespace Cms\Controllers\Admin;

use \Cms\Controllers\Admin\Admin;
use \Cms\Models\PostCustomField;

class PostCustomFields extends Admin {
		/**
	 * GET /posts
	 */
	public function index() {
		$this->postcustomfields	= PostCustomField::all();
		
		$this->respondTo(function(&$format) {
			$format->html; // Render per usual
			$format->json	= function() {
				$this->render(array( 'json' => $this->postcustomfields ));
			};
		});
	}

	/**
	 * GET /posts/1
	 */
	public function show() {
		$this->postcustomfield	= PostCustomField::find($this->params('id'));
		
		$this->respondTo(function(&$format) {
			$format->html; // show.php.html
			$format->json	= function() {
				$this->render(array( 'json' => $this->postcustomfield ));
			};
		});
	}

	/**
	 * GET /posts/new
	 */
	public function _new() {
		$this->postcustomfield	= new PostCustomField();
		
		$this->respondTo(function($format) {
			$format->html; // new.php.html
			$format->json = function() {
				$this->render(array( 'json' => $this->postcustomfield ));
			};
		});
	}

	/**
	 * GET /posts/1/edit
	 */
	public function edit() {
		$this->postcustomfield	= PostCustomField::find($this->params('id'));
	}

	/**
	 * POST /posts
	 */
	public function create() {
		$this->postcustomfield	= new PostCustomField($this->params('postcustomfield'));
		
		$this->respondTo(function($format) {
			if ($this->postcustomfield->save()) {
				$format->html = function() {
					$this->redirectTo($this->admin_post_custom_fields_url(), array("notice" => "PostCustomField was successfully created."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->postcustomfield ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->postcustomfield->errors ));
				};
			}
		});
	}

	/**
	 * PUT /posts/1
	 */
	public function update() {
		$this->postcustomfield	= PostCustomField::find($this->params('id'));
		
		$this->respondTo(function($format) {
			if ($this->postcustomfield->update_attributes($this->params('postcustomfield'))) {
				$format->html = function() {
					$this->redirectTo($this->admin_post_custom_fields_url(), array("notice" => "PostCustomField was successfully updated."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->postcustomfield ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->postcustomfield->errors ));
				};
			}
		});
	}

	/** 
	 * DELETE /posts/1
	 */
	public function destroy() {
		$this->postcustomfield = PostCustomField::find($this->params('id'));
		$this->postcustomfield->destroy();
		
		$this->respondTo(function($format) {
			$format->html = function() { $this->redirectTo($this->admin_post_custom_fields_url()); };
		});
	}
}

?>