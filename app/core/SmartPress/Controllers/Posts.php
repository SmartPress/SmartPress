<?php
namespace SmartPress\Controllers;


use SmartPress\Controllers\Cms;
use SmartPress\Models\Post;

class Posts extends Cms {

	public $type	= 'post';
	


	/**
	 * GET /posts
	 */
	public function index() {
		$this->posts	= Post::all([
				'conditions' => ['type' => $this->type],
				'joins' => 'author'
			]);
	
		$this->respondTo(function(&$format) {
			$format->html; // Render per usual
			$format->json	= function() {
				$this->render(array( 'json' => $this->posts ));
			};
		});
	}
}

?>
