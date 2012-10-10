<?php
namespace Cms\Controllers;


use Cms\Controllers\Cms;
use Cms\Models\Post;

class Posts extends Cms {

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