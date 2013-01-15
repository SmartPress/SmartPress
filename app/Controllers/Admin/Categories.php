<?php
namespace SmartPress\Controllers\Admin;

use \SmartPress\Controllers\Admin\Admin;
use \SmartPress\Models\Category;

class Categories extends Admin {

		/**
	 * GET /posts
	 */
	public function index() {
		$this->category	= Category::all();
		
		$this->respondTo(function(&$format) {
			$format->html; // Render per usual
			$format->json	= function() {
				$this->render(array( 'json' => $this->category ));
			};
		});
	}

	/**
	 * GET /posts/1
	 */
	public function show() {
		$this->category	= Category::find($this->params('id'));
		
		$this->respondTo(function(&$format) {
			$format->html; // show.php.html
			$format->json	= function() {
				$this->render(array( 'json' => $this->category ));
			};
		});
	}

	/**
	 * GET /posts/new
	 */
	public function _new() {
		$this->category	= new Category();
		
		$this->respondTo(function($format) {
			$format->html; // new.php.html
			$format->json = function() {
				$this->render(array( 'json' => $this->category ));
			};
		});
	}

	/**
	 * GET /posts/1/edit
	 */
	public function edit() {
		$this->category	= Category::find($this->params('id'));
	}

	/**
	 * POST /posts
	 */
	public function create() {
		$this->category	= new Category($this->params('category'));
		
		$this->respondTo(function($format) {
			if ($this->category->save()) {
				$format->html = function() {
					$this->redirectTo($this->category, array("notice" => "Category was successfully created."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->category ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->category->errors ));
				};
			}
		});
	}

	/**
	 * PUT /posts/1
	 */
	public function update() {
		$this->category	= Category::find($this->params('id'));
		
		$this->respondTo(function($format) {
			if ($this->category->update_attributes($this->params('category'))) {
				$format->html = function() {
					$this->redirectTo($this->category, array("notice" => "Category was successfully updated."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->category ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->category->errors ));
				};
			}
		});
	}

	/** 
	 * DELETE /posts/1
	 */
	public function destroy() {
		$this->category = Category::find($this->params('id'));
		$this->category->destroy();
		
		$this->respondTo(function($format) {
			$format->html = function() { $this->redirectTo($this->category_url()); };
		});
	}

}

?>
