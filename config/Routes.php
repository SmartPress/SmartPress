<?php
namespace SmartPress\Config;


use Speedy\Router\Draw as SpeedyDraw;
use SmartPress\Lib\Router\Routes\Slug;
use SmartPress\Lib\Module\Site as SiteModules;
use SmartPress\Models\Config\Manager as ConfigManager;
use SmartPress\Models\Config;

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
			
			$this->resources('uploads', ['only' => ['create','index','destroy']]);
			$this->resources('images', ['only' => []], function() {
				$this->member(function() {
					$this->post('resize');
				});
			});
		});
		
		$this->resources('posts', ['only' => ['show', 'index']], function() {
			$this->resources('comments', ['only' => ['create', 'index']]);
		});
		$this->resources('pages', ['only' => 'show']);
		$this->resources('category', ['only' => 'show']);
		
		$this->match(['admin' => 'admin/users#index']);
		$this->match(['admin/signin' => "admin/sessions#_new", 'name' => 'admin_sign_in']);
		$this->match(['admin/signout' => "admin/sessions#destroy", 'name' => 'admin_sign_out']);
		
		$homeType = ConfigManager::get(Config::HomeTypeName);
		if ($homeType == \SmartPress\Models\Post::SinglePageHomeType) {
			$this->rootTo('pages#show', ['id' => ConfigManager::get(Config::HomeSingleName)]);
		} elseif (!$homeType || $homeType == \SmartPress\Models\Post::BlogRollHomeType) {
			$this->rootTo('posts#index');
		}
		
		//var_dump(SiteModules::allPaths());
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
