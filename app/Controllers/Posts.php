<?php
namespace SmartPress\Controllers;


use SmartPress\Controllers\SmartPress;
use SmartPress\Models\Post;

class Posts extends SmartPress {

	public $type	= 'post';
	


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
}

?>
