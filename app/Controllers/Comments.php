<?php
namespace Cms\Controllers\Posts;


use Cms\Models\Comment;
use Cms\Controllers\Application;

class Comments extends Application {
		/**
	 * GET /posts
	 */
	public function index() {
		$this->comments	= Comment::all();
		
		$this->respondTo(function(&$format) {
			$format->html; // Render per usual
			$format->json	= function() {
				$this->render(array( 'json' => $this->comments ));
			};
		});
	}

	/**
	 * GET /posts/1
	 */
	public function show() {
		$this->comment	= Comment::find($this->params('id'));
		
		$this->respondTo(function(&$format) {
			$format->html; // show.php.html
			$format->json	= function() {
				$this->render(array( 'json' => $this->comment ));
			};
		});
	}

	/**
	 * GET /posts/new
	 */
	public function _new() {
		$this->comment	= new Comment();
		
		$this->respondTo(function($format) {
			$format->html; // new.php.html
			$format->json = function() {
				$this->render(array( 'json' => $this->comment ));
			};
		});
	}

	/**
	 * GET /posts/1/edit
	 */
	public function edit() {
		$this->comment	= Comment::find($this->params('id'));
	}

	/**
	 * POST /posts
	 */
	public function create() {
		$comment = $this->params('comment');
		$comment['post_id']	= $this->params('post_id');
		$comment['author_ip']	= (isset($_SERVER['REMOTE_HOST'])) ? $_SERVER['REMOTE_HOST'] : 'Unknown';
		$comment['status']	= Comment::PendingStatus; 
		$this->comment	= new Comment($comment);
		\Speedy\Logger::debug($this->params('post_id'));
		$this->respondTo(function($format) {
			if ($this->comment->save()) {
				$format->html = function() {
					$this->redirectTo($this->post_path($this->params('post_id')), array("notice" => "Comment was successfully created."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->comment ));
				};
			} else {
				$format->html = function() {
					$this->redirectTo($this->post_path($this->params('post_id')), array("notice" => "Comment was not created."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->comment->errors ));
				};
			}
		});
	}

	/**
	 * PUT /posts/1
	 */
	public function update() {
		$this->comment	= Comment::find($this->params('id'));
		
		$this->respondTo(function($format) {
			if ($this->comment->update_attributes($this->params('comment'))) {
				$format->html = function() {
					$this->redirectTo($this->comment, array("notice" => "Comment was successfully updated."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->comment ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->comment->errors ));
				};
			}
		});
	}

	/** 
	 * DELETE /posts/1
	 */
	public function destroy() {
		$this->comment = Comment::find($this->params('id'));
		$this->comment->destroy();
		
		$this->respondTo(function($format) {
			$format->html = function() { $this->redirectTo($this->comments_url()); };
		});
	}
}

?>