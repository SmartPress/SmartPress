<?php
namespace Cms\Config;


use \Speedy\Router\Draw as SpeedyDraw;
use \Cms\Lib\Router\Routes\Slug;

class Routes extends SpeedyDraw {

	public function draw() {
	
		// Resource routing example:
		$this->_namespace('admin', function() {
		
			$this->resources('posts');
			$this->resources('pages');
			
			$this->resources('configs', array(), function() {
				
				$this->match(array('options/:view' => "options"));
				
			});
			
			$this->resources('categories');
			$this->resources('modules');
			$this->resources('groups');
			$this->resources('users');
			
			$this->resources('sessions', ['only' => ['new', 'create', 'destroy']]);
			$this->resources('themes', ['only' => ['create', 'destroy']]);
		});
		
		$this->resources('posts');
		
		$this->match(['/admin/signin' => "admin/sessions#_new"]);
		$this->match(['/admin/signout' => "admin/sessions#destroy"]);
		$this->slug(array("/:slug" => "pages#show", "type" => "page"));
		
		// Match example:
		// $this->match(array( '/posts/edit' => 'posts#edit', 'on' => 'GET' ));
		
		// Root example:
		// $this->rootTo('posts#show', array( 'id' => 1 ));
	
	}
	
	public function slug($params = array()) {
		return $this->pushRoute(new Slug($params));
	}

} 

?>