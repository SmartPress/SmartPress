<?php
namespace Cms\Controllers\Admin;

use \Cms\Controllers\Admin\Admin;
use \Cms\Models\Module;

defined('MODULE_UPLOAD_DIR') or define('MODULE_UPLOAD_DIR', ROOT . DS . 'tmp' . DS . 'uploads');

class Modules extends Admin {

	protected $_mixins = [
		'\\Cms\\Lib\\Helpers\\FileUpload' => [
			'fileModel'	=> '\\Cms\\Models\\Module',
			'allowedTypes'	=> [ 'zip' ],
			'unique'	=> false,
			'forceWebroot'	=> false,
			'uploadDir'	=> MODULE_UPLOAD_DIR,
			'fileVar'	=> 'file'
		]
	];
	
	public $before_filter = [];
	
	
	
	/**
	 * GET /posts
	 */
	public function index() {
		$this->modules	= Module::all();
		
		$this->respondTo(function(&$format) {
			$format->html; // Render per usual
			$format->json	= function() {
				$this->render(array( 'json' => $this->modules ));
			};
		});
	}

	/**
	 * GET /posts/1
	 */
	public function show() {
		$this->module	= Module::find($this->params('id'));
		
		$this->respondTo(function(&$format) {
			$format->html; // show.php.html
			$format->json	= function() {
				$this->render(array( 'json' => $this->module ));
			};
		});
	}

	/**
	 * GET /posts/new
	 */
	public function _new() {
		$this->module	= new Module();
		
		$this->respondTo(function($format) {
			$format->html; // new.php.html
			$format->json = function() {
				$this->render(array( 'json' => $this->module ));
			};
		});
	}

	/**
	 * GET /posts/1/edit
	 */
	public function edit() {
		$this->module	= Module::find($this->params('id'));
	}

	/**
	 * POST /posts
	 */
	public function create() {
		$this->module	= new Module($this->params('module'));
		
		
		$this->respondTo(function($format) {
			if ($this->module->save()) {
				$format->html = function() {
					$this->redirectTo($this->module, array("notice" => "Module was successfully created."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->module ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->module->errors ));
				};
			}
		});
	}

	/**
	 * PUT /posts/1
	 */
	public function update() {
		$this->module	= Module::find($this->params('id'));
		
		$this->respondTo(function($format) {
			if ($this->module->update_attributes($this->params('module'))) {
				$format->html = function() {
					$this->redirectTo($this->module, array("notice" => "Module was successfully updated."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->module ));
				};
			} else {
				$format->html = function() {
					$this->render("new");
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->module->errors ));
				};
			}
		});
	}

	/** 
	 * DELETE /posts/1
	 */
	public function destroy() {
		$this->module = Module::find($this->params('id'));
		$this->module->destroy();
		
		$this->respondTo(function($format) {
			$format->html = function() { $this->redirectTo($this->modules_url()); };
		});
	}

}

?>