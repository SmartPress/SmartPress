<?php
namespace Cms\Config;

import('speedy.router.draw');
use \Speedy\Router\Draw as SpeedyDraw;
use \Cms\Lib\Router\Routes\Slug;

class Routes extends SpeedyDraw {

	public function draw() {
	
		// Resource routing example:
		$this->_namespace('admin', function() {
		
			$this->resources('posts');
			$this->resources('pages');
			$this->resources('configs');
			
		});
		
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