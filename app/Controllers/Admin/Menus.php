<?php
namespace Cms\Controllers\Admin;


use \Cms\Controllers\Admin\Admin;
use \Cms\Models\Menu;

class Menus extends Admin {
		/**
	 * GET /posts
	 */
	public function index() {
		$this->menus	= Menu::all();
		
		$this->respondTo(function($format) {
			$format->html; // Render per usual
			$format->json	= function() {
				$this->render(array( 'json' => $this->menus ));
			};
		});
	}

	/**
	 * GET /posts/1
	 */
	public function show() {
		$this->menu	= Menu::find($this->params('id'));
		
		$this->respondTo(function($format) {
			$format->html; // show.php.html
			$format->json	= function() {
				$this->render(array( 'json' => $this->menu ));
			};
		});
	}

	/**
	 * GET /posts/new
	 */
	public function _new() {
		$this->menu	= new Menu();
		
		$this->respondTo(function($format) {
			$format->html; // new.php.html
			$format->json = function() {
				$this->render(array( 'json' => $this->menu ));
			};
		});
	}

	/**
	 * GET /posts/1/edit
	 */
	public function edit() {
		$this->menu	= Menu::find($this->params('id'));
	}

	/**
	 * POST /posts
	 */
	public function create() {
		$this->menu	= new Menu($this->params('menu'));
		
		$this->respondTo(function($format) {
			if ($this->menu->save()) {
				$format->html = function() {
					$this->redirectTo($this->menu, array("notice" => "Menu was successfully created."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->menu ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->menu->errors ));
				};
			}
		});
	}

	/**
	 * PUT /posts/1
	 */
	public function update() {
		$this->menu	= Menu::find($this->params('id'));
		
		$this->respondTo(function($format) {
			if ($this->menu->update_attributes($this->params('menu'))) {
				$format->html = function() {
					$this->redirectTo($this->menu, array("notice" => "Menu was successfully updated."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->menu ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->menu->errors ));
				};
			}
		});
	}

	/** 
	 * DELETE /posts/1
	 */
	public function destroy() {
		$this->menu = Menu::find($this->params('id'));
		$this->menu->destroy();
		
		$this->respondTo(function($format) {
			$format->html = function() { $this->redirectTo($this->menus_url()); };
		});
	}
}

?>