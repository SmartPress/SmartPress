<?php
namespace Cms\Controllers;

use \Cms\Controllers\Application;
use \Cms\Models\Post;

class Cms extends Application {

	public $type	= "page";
	


	/**
	 * GET /posts/1
	 */
	public function show() {
		$this->post	= Post::find($this->params('id'), array( 'conditions' => array( 'type' => $this->type )));
		
		$this->respondTo(function(&$format) {
			$format->html; // show.php.html
			$format->json	= function() {
				$this->render(array( 'json' => $this->post ));
			};
		});
	}

}

?>