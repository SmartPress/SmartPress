<?php
namespace SmartPress\Controllers\Admin;


use SmartPress\Controllers\Admin\Admin;
use SmartPress\Models\Menu;
use SmartPress\Lib\Concerns\Exceptions\TreeException;

class Menus extends Admin {
		/**
	 * GET /posts
	 */
	public function index() {
		$this->menu	= new Menu();
		$menus	= (array)Menu::tree();
		array_unshift($menus, new Menu(['title' => 'New Menu']));
		$this->allMenus	= $menus;
		$this->menus	= Menu::allMenus();
		
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
		$menus	= (array)Menu::tree();
		array_unshift($menus, new Menu(['title' => 'Select One']));
		$this->allMenus	= $menus; 
		
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
					$this->redirectTo($this->admin_menus_url(), array("notice" => "Menu was successfully created."));
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
					$this->redirectTo($this->admin_menus_url(), array("notice" => "Menu was successfully updated."));
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
			$format->html = function() { 
				$this->redirectTo($this->admin_menus_url()); 
			};
		});
	}
	
	/**
	 * POST /admin/menu/1/move
	 */
	public function move() {
		//$	= Menu::find($this->params('id'));	
	
		$this->respondTo(function($format) {
			/*$format->json	= function() {
				$this->render(['json' => ['offset'	=> $this->params('offset')]]);
			};*/
			try {
				Menu::move($this->params('id'), $this->params('offset'));
				$format->json	= function() {
					$this->render(['json' => ['menu' => $this->menu, 'params' => $this->params()]]);
				};
			} catch (TreeException $e) {
				$format->json	= function() {
					$this->render(['json' => [
							'error' => $e->getMessage(), 
							'success' => false, 
							'trace'	=> $e->getTrace,
							'exception' => $e]]);
				};
			} catch (Exception $e) {
				$format->json	= function() {
					$this->render(['json' => [
							'error' => $e->getMessage(), 
							'success' => false, 
							'exception' => $e]]);
				};
			}
		});
	}
}

?>
