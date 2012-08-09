<?php
namespace Cms\Controllers\Admin;

use \Cms\Controllers\Admin\Admin;
use \Cms\Models\Module;
use \Cms\Lib\Module\Installer as ModInstaller;
use \ZipArchive;
use \ArrayObject;

defined('MODULE_UPLOAD_DIR') or define('MODULE_UPLOAD_DIR', ROOT . DS . 'tmp' . DS . 'uploads');

class Modules extends Admin {

	protected $_mixins = [
		'\\Cms\\Lib\\Helpers\\FileUpload' => [
			'allowedTypes'	=> [ 'zip' ],
			'unique'	=> false,
			'forceWebroot'	=> false,
			'uploadDir'	=> MODULE_UPLOAD_DIR,
			'fileVar'	=> 'module',
			'fileModel' => null,
			'alias'		=> 'FileUpload'
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
		//$this->module	= new Module($this->params('module'));
		$uploadSuccess 	= $this->mixin('FileUpload')->success();
		$processSuccess	= false;
		$zips	= ($uploadSuccess) ? $this->mixin('FileUpload')->finalFiles() : null;

		$this->respondTo(function($format) use ($zips) {
			if ($zips && ModInstaller::instance()->processZip(TMP_PATH . DS . 'uploads' . DS . array_shift($zips))) {
				$this->module = ModInstaller::instance()->record();
				$format->html = function() {
					$this->redirectTo($this->admin_modules_url(), array("notice" => "Module was successfully created."));
				};
				$format->json = function() {
					$this->render(array( 'json' => $this->module ));
				};
			} else {
				$format->html = function() {
					$this->module->errors = new ArrayObject([ModInstaller::instance()->error()]);
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
			if (ModInstaller::instance()->update($this->module->id)) {
				$format->html = function() {
					$this->redirectTo($this->admin_module_path($this->module), array("notice" => "Module was successfully updated."));
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
			$format->html = function() { $this->redirectTo($this->admin_modules_url()); };
		});
	}
	
}

?>