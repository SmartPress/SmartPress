<?php
namespace SmartPress\Controllers\Admin;

use \SmartPress\Controllers\Admin\Admin;
use \SmartPress\Models\Config;
use \SmartPress\Models\Event\Manager as EventManager;
use \Speedy\View;

class Configs extends Admin {
	
	protected $minReadPrivilege	= SuperAdminPrivilege;
	
	protected $minWritePrivilege	= SuperAdminPrivilege;
	

	/**
	 * GET /configs
	 */
	public function index() {
		$this->configs	= Config::all();
		
		$this->respondTo(function(&$format) {
			$format->html; // Render per usual
			$format->json	= function() {
				$this->render(array( 'json' => $this->configs ));
			};
		});
	} 

	/**
	 * GET /configs/1
	 */
	public function show() {
		$this->config	= Config::find($this->params('id'));
		
		$this->respondTo(function(&$format) {
			$format->html; // show.php.html
			$format->json	= function() {
				$this->render(array( 'json' => $this->config ));
			};
		});
	}

	/**
	 * GET /configs/new
	 */
	public function _new() {
		$this->config	= new Config();
		
		$this->respondTo(function($format) {
			$format->html; // new.php.html
			$format->json = function() {
				$this->render(array( 'json' => $this->config ));
			};
		});
	}

	/**
	 * GET /configs/1/edit
	 */
	public function edit() {
		$id = $this->params('id');
		$viewAlias	= "admin/configs/section/$id";
		$view		= View::instance()->findFile($viewAlias);
	
		if (!$view) {
			$this->config	= Config::find($this->params('id'));
		}
		
		$this->respondTo(function($format) use ($viewAlias, $view) {
			if ($view) {
				$format->html = function() use ($viewAlias) {
					$this->render($viewAlias);
				};
			}
		});
	}

	/**
	 * POST /configs
	 */
	public function create() {
		$configs= $this->params('config');
		$success= false;
		$errors = null;
		
		if (isset($configs[0]) && is_array($configs[0])) {
			$this->config	= new Config();
			
			foreach ($configs as $config) {
				$existingConfig	= Config::find_by_name($config['name']);
				$finalConfig = null;
					
				if ($existingConfig) {
					$success = $existingConfig->update_attributes($config);
					if (!$success) $errors = $existingConfig->errors;
					$finalConfig = $existingConfig;
				} else {
					$newConfig = new Config($config);
					$success = $newConfig->save();
					if (!$success) $errors = $newConfig->errors;
					$finalConfig = $newConfig;
				}
					
				if (!$success) {
					break;
				} else {
					$name = str_replace('/', '_', $config['name']);
					EventManager::dispatch('admin_configs_update_' . $name, $finalConfig);
				}
			}
		} else {
			$this->config	= new Config($configs);
			$success = $this->config->save();
		}
		
		$this->respondTo(function($format) use($success, $errors) {
			if ($success) {
				$format->html = function() {
					$this->redirectTo($this->admin_configs_url(), array("notice" => "Settings saved successfully."));
				};
				$format->json = function() {
					$this->render(array( 'json' => ['success' => true] ));
				};
			} else {
				$format->html = function() use ($errors) {
					$this->render("new");
				};
				$format->json = function() use ($errors) {
					$this->render(array( 'json' => $errors ));
				};
			}
		});
	}

	/**
	 * PUT /configs/1
	 */
	public function update() {
		$this->config	= Config::find($this->params('id'));
		
		$this->respondTo(function($format) {
			if ($this->config->update_attributes($this->params('config'))) {
				$name = str_replace('/', '_', $this->config->name);
				EventManager::dispatch('admin_configs_update_' . $name);
				
				$format->html = function() {
					$this->redirectTo($this->admin_configs_url(), array("notice" => "Config was successfully updated."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->config ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->config->errors ));
				};
			}
		});
	}

	/** 
	 * DELETE /configs/1
	 */
	public function destroy() {
		$this->config = Config::find($this->params('id'));
		$this->config->destroy();
		
		$this->respondTo(function($format) {
			$format->html = function() { $this->redirectTo($this->admin_configs_url()); };
		});
	}
	
	/**
	 * POST /configs/save
	 */
	public function save() {
	
	}
	
	/**
	 * GET /configs/options/:view
	 */
	public function options() {
		debug($this->params('view'));
	}

}

?>
