<?php
namespace SmartPress\Controllers\Admin;


use SmartPress\Controllers\Admin\Admin;
use SmartPress\Models\Block;
use SmartPress\Models\Block\Manager as BlockManager;
use SmartPress\Models\Post;
use SmartPress\Models\PostCustomField;
use SmartPress\Models\PostCategory;
use Speedy\Utility\Inflector;
use Speedy\Loader;
use Speedy\Logger;

class Cms extends Admin {
	
	public $type	= 'page';

	public $before_filter	= array();

	
	
	/**
	 * GET /posts
	 */
	public function index() {
		$this->posts	= Post::paginate(
				$this, 
				20,
				array('conditions' => array( 'type' => $this->type ))
		);
		$this->pagination = Post::paginationVars();
		
		$this->respondTo(function(&$format) {
			$format->html; // Render per usual
			$format->json	= function() {
				$this->render(array( 'json' => $this->posts ));
			};
		});
	}
	
	/**
	 * GET /posts/1
	 */
	public function show() {
		$this->post	= Post::first(array(
			'conditions' => [
				'id = ? AND type = ?', $this->params('id'), $this->type
			]
		));
		$this->layout	= $this->post->layout;
		
		$this->respondTo(function(&$format) {
			$format->html; // show.php.html
			$format->json	= function() {
				$this->render(array( 'json' => $this->post ));
			};
		});
	}
	
	/**
	 * GET /posts/new
	 */
	public function _new() {
		$this->post	= new Post(array( 'type' => $this->type ));
		$this->post_custom_fields = PostCustomField::all();
		
		$path	= "admin_" . Inflector::pluralize($this->type) . "_url";
		$this->action_path	= $this->{$path}();
		
		$this->respondTo(function($format) {
			$format->html; // new.php.html
			$format->json = function() {
				$this->render(array( 'json' => $this->post ));
			};
		});
	}
	
	/**
	 * GET /posts/1/edit
	 */
	public function edit() {
		$this->post	= Post::first([
					'conditions' => [
						'id = ? AND type = ?', $this->params('id'), $this->type
					]
				]);
		$this->post_custom_fields = PostCustomField::all();
		$this->blocks	= BlockManager::availableBlocks();
		
		$path	= "admin_{$this->type}_path";
		$this->action_path	= $this->{$path}($this->post->id);
	}
	
	/**
	 * POST /posts
	 */
	public function create() {
		$data			= $this->params('post');
		$data['type']	= $this->type;
		$this->post		= new Post($data);
		
		$this->respondTo(function($format) {
			if ($this->post->save()) {
				$format->html = function() {
					$plural	= Inflector::pluralize($this->type);
					$path	= "admin_{$plural}_url";
					$this->redirectTo($this->{$path}(), array("notice" => ucfirst($this->type) . " was successfully created."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->post ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->post->errors ));
				};
			}
		});
	}
	
	/**
	 * PUT /posts/1
	 */
	public function update() {
		$this->post	= Post::first([
				'conditions' => [
					'id = ? AND type = ?',
					$this->params('id'),
					$this->type 
				]
			]);
		
		$this->respondTo(function($format) {
			$data			= $this->params('post');
			$data['type']	= $this->type;
			$categories 	= $this->params('post_category');
			if ($this->post->update_attributes($data)) {
				if ($categories && count($categories) > 0) {
					foreach ($categories as $cat) {
						PostCategory::create(['post_id' => $this->post->id, 'category_id' => $cat]);
					}
				}

				$format->html = function() {
					$plural	= Inflector::pluralize($this->type);
					$path	= "admin_{$plural}_url";
					$this->redirectTo($this->{$path}(), array("notice" => ucfirst($plural) . " was successfully updated."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->post ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->post->errors ));
				};
			}
		});
	}
	
	/** 
	 * DELETE /posts/1
	 */
	public function destroy() {
		$this->post = Post::first([
				'conditions' => [
					'id = ? AND type = ?',
					$this->params('id'),
					$this->type
				]
			]);
		$this->post->destroy();
		
		$this->respondTo(function($format) {
			$format->html = function() { 
				$plural	= Inflector::pluralize($this->type);
				$path	= "admin_{$plural}_url";
				$this->redirectTo($this->{$path}()); 
			};
		});
	}
	
}

?>
