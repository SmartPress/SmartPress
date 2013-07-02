<?php
namespace SmartPress\Controllers;


use SmartPress\Controllers\Application;
use SmartPress\Models\Post;
use SmartPress\Models\Theme;
use SmartPress\Models\Comment;
use Speedy\Utility\Inflector;

class Cms extends Application {

	public $type	= "page";
	


	/**
	 * GET /posts/1
	 */
	public function show() {
		$this->post	= Post::find_by_slug_or_id($this->params('id'), $this->type);
		
		if (!empty($this->post->layout) && in_array($this->post->layout, Theme::availableLayouts())) {
			$this->layout	= $this->post->layout;
		}

		$this->comment = new Comment();

		$this->altView = "show-{$this->layout}";
		$this->showAltView = false;
		$path = Inflector::pluralize($this->type) . DS . $this->altView;
		if (\Speedy\View::instance()->findFile($path) !== false)
			$this->showAltView = true;

		$this->title_for_layout = isset($this->post->custom_data['title']) ?
			$this->post->custom_data['title'] : $this->post->title;
		$this->meta = $this->post->meta();
		
		$this->respondTo(function(&$format) {
			if ($this->showAltView) {
				$format->html = function() {
					$this->render($this->altView);
				};
			} else {
				$format->html; // show.php.html
			}

			$format->json	= function() {
				$this->render(array( 'json' => $this->post ));
			};
		});
	}

}

?>
