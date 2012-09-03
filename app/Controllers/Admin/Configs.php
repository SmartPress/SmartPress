<?php
namespace Cms\Controllers\Admin;

use \Cms\Controllers\Admin\Admin;
use \Cms\Models\Config;
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
		
		$this->respondTo(function($format) use ($viewAlias) {
			$format->html = function() use ($viewAlias) {
				if ($viewAlias) $this->render($viewAlias);
			};
		});
	}

	/**
	 * POST /configs
	 */
	public function create() {
		$configs	= $this->params('config');
		$success	= false;
		$this->config	= new Config();
		$errors = null;
		
		foreach ($configs as $config) {
			$existingConfig	= Config::find_by_name($config['name']);
			
			if ($existingConfig) {
				$success = $existingConfig->update_attributes($config);
				if (!$success) $errors = $existingConfig->errors;
			} else {
				$newConfig = new Config($config);
				$success = $newConfig->save();
				if (!$success) $errors = $newConfig->errors;
			}
			
			if (!$success) {
				break;
			}
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