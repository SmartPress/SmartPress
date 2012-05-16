<?php
namespace Cms\Controllers\Admin;

use \Cms\Controllers\Admin\Admin;
use \Cms\Models\Post;
use \Speedy\Utility\Inflector;

class Cms extends Admin {

	public $type	= 'page';

	public $before_filter	= array();

	
	
	/**
	 * GET /posts
	 */
	public function index() {
		$this->posts	= Post::all(array('conditions' => array( 'type' => $this->type )));
		
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
		$this->post	= Post::find($this->params('id'), array(
			'conditions' => array(
				'type'	=> $this->type
			)
		));
		
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
		$this->post	= Post::find($this->params('id', array( 'type' => $this->type )));
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
					$path	= "admin_{$this->type}_path";
					$this->redirectTo($this->{$path}($this->post->id), array("notice" => ucfirst($this->type) . " was successfully created."));
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
		$this->post	= Post::find($this->params('id'), array( 'conditions' => array( 'type' => $this->type )));
		
		$this->respondTo(function($format) {
			$data			= $this->params('post');
			$data['type']	= $this->type;
			if ($this->post->update_attributes($data)) {
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
		$this->post = Post::find($this->params('id'), array( 'conditions' => array( 'type' => $this->type )));
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