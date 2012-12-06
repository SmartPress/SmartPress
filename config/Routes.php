<?php
namespace Cms\Config;


use Speedy\Router\Draw as SpeedyDraw;
use Cms\Lib\Router\Routes\Slug;
use Cms\Lib\Module\Site as SiteModules;
use Cms\Models\Config\Manager as ConfigManager;

class Routes extends SpeedyDraw {

	public function draw() {
	
		// Resource routing example:
		$this->_namespace('admin', function() {
		
			$this->resources('posts');
			$this->resources('pages');
			$this->resources('post_custom_fields');
			
			$this->resources('configs', array(), function() {
				
				$this->match(array('options/:view' => "options"));
				
			});
			
			$this->resources('categories');
			$this->resources('modules');
			$this->resources('groups');
			$this->resources('users');
			$this->resources('blocks', [], function() {
				$this->collection(function() {
					$this->get('new_with_ns');
					$this->get('fields');
				});
			});
			
			$this->resources('sessions', ['only' => ['new', 'create', 'destroy']]);
			$this->resources('themes', ['only' => ['create', 'destroy']]);
			$this->resources('menus', [], function() {
				$this->member(function() {
					$this->post('move');
				});
			});
			
			$this->resources('comments', [], function() {
				$this->member(function() {
					$this->get('approve');
					$this->get('disapprove');
				});
			});
		});
		
		$this->resources('posts', ['only' => ['show', 'index']], function() {
			$this->resources('comments', ['only' => ['create', 'index']]);
		});
		$this->resources('pages', ['only' => 'show']);
		
		$this->match(['admin/signin' => "admin/sessions#_new"]);
		$this->match(['admin/signout' => "admin/sessions#destroy"]);
		
		$homeType = ConfigManager::get('home/type');
		if ($homeType == 2) {
			$this->rootTo('pages#show', ['id' => ConfigManager::get('home/single_id')]);
		} elseif ($homeType == 1) {
			$this->rootTo('posts#index');
		}
		
		foreach (SiteModules::allPaths() as $path) {
			$routes = $path . DS . 'Etc' . DS . 'Routes.php';
			if (!file_exists($routes)) {
				continue;
			}
			
			include_once $routes;
		}
		
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