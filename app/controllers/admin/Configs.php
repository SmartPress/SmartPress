<?php
namespace Cms\Controllers\Admin;

use \Cms\Controllers\Admin\Admin;
use \Cms\Models\Config;

class Configs extends Admin {

	/**
	 * GET /posts
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
	 * GET /posts/1
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
	 * GET /posts/new
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
	 * GET /posts/1/edit
	 */
	public function edit() {
		$this->config	= Config::find($this->params('id'));
	}

	/**
	 * POST /posts
	 */
	public function create() {
		$this->config	= new Config($this->params('config'));
		
		$this->respondTo(function($format) {
			if ($this->config->save()) {
				$format->html = function() {
					$this->redirectTo($this->admin_configs_url(), array("notice" => "Config was successfully created."));
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
	 * PUT /posts/1
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
	 * DELETE /posts/1
	 */
	public function destroy() {
		$this->config = Config::find($this->params('id'));
		$this->config->destroy();
		
		$this->respondTo(function($format) {
			$format->html = function() { $this->redirectTo($this->admin_configs_url()); };
		});
	}

}

?>