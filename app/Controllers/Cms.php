<?php
namespace SmartPress\Controllers;


use SmartPress\Controllers\Application;
use SmartPress\Models\Post;
use SmartPress\Models\Theme;
use SmartPress\Models\Comment;

class SmartPress extends Application {

	public $type	= "page";
	


	/**
	 * GET /posts/1
	 */
	public function show() {
		$this->post	= Post::find('first', array( 
				'conditions' => ['(id = ? OR slug = ?) AND type = ?', $this->params('id'), $this->params('id'), $this->type]
				));
		if (!empty($this->post->layout) && in_array($this->post->layout, Theme::availableLayouts())) {
			$this->layout	= $this->post->layout;
		}
		$this->comment = new Comment();
		
		$this->respondTo(function(&$format) {
			$format->html; // show.php.html
			$format->json	= function() {
				$this->render(array( 'json' => $this->post ));
			};
		});
	}

}

?>
