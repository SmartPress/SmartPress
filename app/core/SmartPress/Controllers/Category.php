<?php
namespace SmartPress\Controllers;


use SmartPress\Controllers\Application;
use SmartPress\Models\Post;
use SmartPress\Models\PostCategory;
use SmartPress\Models\Category as CategoryModel;

class Category extends Application {

	public $layout = 'blog';


	/**
	 * GET /posts/1
	 */
	public function show() {
		$categories	= (array) CategoryModel::all([
				'conditions'=> [
					'p.id = ? OR p.slug = ? OR (c.lft >= p.lft AND c.lft <= p.rght)', 
					$this->params('id'), 
					$this->params('id')
				],
				'from'		=> 'categories as p, categories as c',
				'order'		=> 'c.lft ASC',
				'select'	=> 'c.id, c.name, c.slug'
			]);
		$ids = [];
		foreach ($categories as $category) {
			$ids[] = $category->id;
		}
		$this->category = array_shift($categories);

		$this->posts = Post::paginate($this, 10, [
				'conditions' => [
					'posts.id IN (SELECT pc.post_id FROM post_categories AS pc
						WHERE pc.category_id IN (?)
					)',
					$ids
				],
				'joins' => ['author'],
				'order'	=> 'posts.created_at DESC'
			]);

		$this->pagination = Post::paginationVars();
		$this->title_for_layout = $this->category->name;
		
		$this->respondTo(function(&$format) {
			$format->html; // show.php.html
			$format->json	= function() {
				$this->render(array( 'json' => [
						'category' 	=> $this->category,
						'posts'		=> $this->posts
					]));
			};
		});
	}

}

?>
