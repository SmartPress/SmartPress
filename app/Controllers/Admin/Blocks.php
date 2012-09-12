<?php
namespace Cms\Controllers\Admin;


use \Cms\Controllers\Admin\Admin;
use \Cms\Models\Block;

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
				$this->render(array( 'json' => $this->block ));
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
					$this->redirectTo($this->admin_block_path($this->block), array("notice" => "Block was successfully created."));
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
					$this->redirectTo($this->admin_block_path($this->block), array("notice" => "Block was successfully updated."));
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