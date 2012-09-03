<?php
namespace Cms\Controllers\Admin;


use \Cms\Controllers\Admin\Admin;
use \Cms\Models\Theme;
use \Cms\Lib\Exceptions\Theme as ThemeException;
use \Speedy\Cache;
use \ZipArchive;
use \Exception;

defined('TEMPLATE_UPLOAD_DIR') or define('TEMPLATE_UPLOAD_DIR', ROOT . DS . 'tmp' . DS . 'uploads');

class Themes extends Admin {
	
	protected $_mixins = [
		'\\Cms\\Lib\\Helpers\\FileUpload' => [
			'allowedTypes'	=> [ 'zip' ],
			'unique'	=> false,
			'forceWebroot'	=> false,
			'uploadDir'	=> TEMPLATE_UPLOAD_DIR,
			'fileVar'	=> 'theme',
			'fileModel' => null,
			'alias'		=> 'FileUpload'
		],
		'\\Speedy\\Controller\\Helper\\Session' => [ 'alias' => 'Session']
	];
	
	
	/**
	 * POST /admin/themes
	 */
	public function create() {
		$uploadSuccess 	= $this->mixin('FileUpload')->success();
		$zips	= ($uploadSuccess) ? $this->mixin('FileUpload')->finalFiles() : null;
		
		$this->respondTo(function($format) use ($zips) {
			try {
				if (empty($zips)) throw new ThemeException('Unable to detect zip');
				Theme::instance()->install(TEMPLATE_UPLOAD_DIR . array_shift($zips));
				Cache::clear(Theme::CacheName);
				
				$format->html = function() {
					$this->redirectTo($this->edit_admin_config_path('look'), ['notice' => 'Uploaded theme successfully']);
				};
				$format->json = function() {
					$this->render(['success' => true]);
				};
			} catch (ThemeException $e) {
				$format->html = function() {
					$this->redirectTo($this->edit_admin_config_path('look'), ['error' => 'Failed uploading theme. ' . $e->getMessage()]);
				};
				$format->json = function() {
					$this->render(['success' => false, 'error' => $e->getMessage]);
				};
			} 
		});
	}
	
	/**
	 * DELETE /admin/themes/1
	 */
	public function destroy() {
		$themeName	= $this->params('id');
		$this->themeDir	= Theme::DIR . DS . $themeName;
		
		$this->respondTo(function($format) {
			if (is_dir($this->themeDir) && rmdir($this->themeDir)) {
				$format->html = function() {
					$this->redirectTo($this->edit_admin_config_path('look'), ['notice' => 'Deleted theme successfully']);
				};
				$format->json = function() {
					$this->render(['success' => true]);
				};
			} else {
				$message = 'Unable to delete theme';
				$format->html = function() {
					$this->redirectTo($this->edit_admin_config_path('look'), ['error' => $message]);
				};
				$format->json = function() {
					$this->render(['success' => false, 'error' => $message]);
				}
			}
		}); 
	}
}

?>