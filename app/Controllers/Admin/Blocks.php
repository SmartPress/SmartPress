<?php
namespace Cms\Controllers\Admin;


use \Cms\Controllers\Admin\Admin;
use \Cms\Models\Block;
use \Cms\Models\Block\Manager;
use \Speedy\Loader;
use \Speedy\Session;
use \Speedy\View;

class Blocks extends Admin {
		/**
	 * GET /posts
	 */
	public function index() {
		$this->blocks	= Block::all();
		
		$this->respondTo(function(&$format) {
			$format->html; // Render per usual
			$format->json	= function() {
				$this->render(array( 'json' => $this->blocks ));
			};
		});
	}

	/**
	 * GET /posts/1
	 */
	public function show() {
		$this->block	= Block::find($this->params('id'));
		
		$this->respondTo(function(&$format) {
			$format->html; // show.php.html
			$format->json	= function() {
				$this->render(array( 'json' => $this->block ));
			};
		});
	}

	/**
	 * GET /posts/new
	 */
	public function _new() {
		$this->block	= new Block();
		
		$this->respondTo(function($format) {
			$format->html; // new.php.html
			$format->json = function() {
				$this->render(array( 
						'json' => [
							'template' => View::instance()->setVars([])->setParams($this->params())->renderToString(
								'admin/blocks/_form', 
								[]
							)
						] ));
			};
		});
	}
	
	public function new_with_ns() {
		$block	= $this->params('block');
		$scopes	= $this->params('scope');
		
		$class	= Loader::instance()->toClass($block);
		$this->vars	= [
			'info'	=> $class::info(),
			'scopes'=> Manager::scopes($scopes['controller'], $scopes['action'])
		];
		
		$this->respondTo(function($format) {
			$format->html; // new.php.html
			$format->json = function() {
				$this->render(array(
						'json' => [
						'template' => View::instance()->setVars($this->vars)
							->setParams($this->params())
							->renderToString(
								'admin/blocks/_class_form',
								[]
							)
					] ));
			};
		});
	}

	/**
	 * GET /posts/1/edit
	 */
	public function edit() {
		$this->block	= Block::find($this->params('id'));
	}

	/**
	 * POST /posts
	 */
	public function create() {
		$this->block	= new Block($this->params('block'));
		
		$this->respondTo(function($format) {
			if ($this->block->save()) {
				$format->html = function() {
					$this->redirectTo(Session::instance()->read('back_url'), array("notice" => "Block was successfully created."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->block ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->block->errors ));
				};
			}
		});
	}

	/**
	 * PUT /posts/1
	 */
	public function update() {
		$this->block	= Block::find($this->params('id'));
		
		$this->respondTo(function($format) {
			if ($this->block->update_attributes($this->params('block'))) {
				$format->html = function() {
					$this->redirectTo(Session::instance()->read('back_url'), array("notice" => "Block was successfully updated."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->block ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->block->errors ));
				};
			}
		});
	}

	/** 
	 * DELETE /posts/1
	 */
	public function destroy() {
		$this->block = Block::find($this->params('id'));
		$this->block->destroy();
		
		$this->respondTo(function($format) {
			$format->html = function() { $this->redirectTo($this->admin_blocks_url()); };
		});
	}
}

?>