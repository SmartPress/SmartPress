<?php 
namespace Cms\Controllers\Admin;


use Cms\Controllers\Admin\Admin;
use Cms\Models\Upload;
use Speedy\Utility\File;

class Uploads extends Admin {
	
	protected $minReadPrivilege	= AdminPrivilege;
	
	protected $minWritePrivilege= AdminPrivilege;
	
	public $layout = null;
	
	protected $_mixins = [
		'\\Cms\\Lib\\Helpers\\FileUpload' => [
			'allowedTypes'	=> [ 'jpeg', 'jpg', 'gif', 'png' ],
			//'allowedTypes'	=> ['zip'],
			'unique'	=> true,
			'forceWebroot'	=> false,
			'uploadDir'	=> PUBLIC_UPLOADS_DIR,
			'fileVar'	=> 'upload',
			'fileModel' => '\\Cms\\Models\\Upload',
			'alias'		=> 'FileUpload',
			'automatic'	=> false,
			'fileNameFunction' => '\\Speedy\\Utility\\Inflector::underscore',
			'fields'	=> [
				'name'	=> 'filename',
				'type'	=> 'type',
				'size'	=> 'size'
			]
		],
		'\\Speedy\\Controller\\Helper\\Session' => [ 'alias' => 'Session']
	];
	
	
	
	public function index() {
		$files = Upload::all();
		$return = [];
		foreach ($files as $file) {
			$return[]	= [
				'filename'	=> $file->filename, 
				'id'	=> $file->id,
				'width'	=> $file->width,
				'height'=> $file->height
			];
		}
		$this->files = $return;
		
		$this->respondTo(function($format) {
			$format->json	= function() {
				$this->render(['json' => [
							'files'	=> $this->files
						]]);
			};
		});
	}
	
	public function create() {
		$this->funcNumber	= $this->params('CKEditorFuncNum');
		$this->ckEditor		= $this->params('CKEditor');
		$this->langCode		= $this->params('langCode');
		
		if (!file_exists(UPLOADS_DIR)) {
			File::mkdir_p(UPLOADS_DIR, 0755);
		}
		
		$this->respondTo(function($format) {
			try {
				$this->mixin('FileUpload')->processAllFiles();
				
				if ($this->mixin('FileUpload')->success()) {
					$this->images	= $this->mixin('FileUpload')->finalFiles();
					$this->message	= "Successfully uploaded file";
				
					$format->json = function() {
						$this->render(['json' => ['message' => $this->message, 'image' => $this->images[0]]]);
					};
					$format->jsonp = function() {
						$this->render();
					};
				} else {
					$this->message	= "Failed to uploaded";
					$this->errors 	= $this->mixin('FileUpload')->showErrors();
				
					$format->json	= function() {
						$this->render(['json' => ['message' => $this->message, 'errors' => $this->errors, 'success' => false]]);
					};
					$format->jsonp	= function() {
						$this->render('fail');
					};
				}	
			} catch (\Cms\Lib\Exceptions\Uploader $e) {
				$this->exception	= $e;
				
				$format->json = function() {
					$this->render(['json' => ['success' => false, 'errors' => $e->getMessage(), 'stack' => $e->getTrace()]]);
				};
				$format->jsonp	= function() {
					$this->render('exception');
				};
			}
		});
	}
	
	public function destroy() {
		$this->file = Upload::find($this->params('id'));
		
		$this->respondTo(function($format) {
			if ($this->file->destroy()) {
				$format->html	= function() {
					$this->redirectTo($this->admin_uploads_url(), ['success' => 'Successfully deleted uploaded file.']);
				};
				$format->json	= function() {
					$this->render(['json' => [
								'success'	=> true
							]]);
				};
			} else {
				$this->message = 'Unable to delete uploaded file.';
				
				$format->html	= function() {
					$this->redirectTo($this->admin_uploads_url(), ['error' => $this->message]);
				};
				$format->json	= function() {
					$this->render(['json' => [
								'success'	=> false,
								'error'		=> $this->message
							]]);
				};
			}
			
		});
	}
	
}
?>