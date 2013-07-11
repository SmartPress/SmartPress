<?php
namespace SmartPress\Controllers\Admin;

use \SmartPress\Controllers\Application;
use \SmartPress\Models\User;
use \SmartPress\Lib\Module\Site as SiteModules;
use \Speedy\Cache;
use \Speedy\Request;


define('ReadPrivilege',	1);
define('WritePrivilege',2);
define('SuperAdminPrivilege', 128);


class Admin extends Application {

	public $layout	= "admin/default";

	protected $beforeFilter = ['__setBackUrl', 'adminMenus', '__validateApi', '_checkPrivilege'];
	
	protected $minReadPrivilege	= ReadPrivilege;
	
	protected $minWritePrivilege= SuperAdminPrivilege;
	
	protected $_mixins = ['\\Speedy\\Controller\\Helper\\Session' => [ 'alias' => 'Session']];
	
	
	
	protected function adminMenus() {
		//$this->menus = Cache::read("module_menus");
		if (!empty($this->menus)) {
			return;
		} 
		
		$menus = [
			'modules' => [],
			'settings' => [],
			'main'	=> []
		];
		foreach (SiteModules::all() as $module) {
			if (!isset($module['menus']) || empty($module['menus']['link'])) {
				continue;
			}
			
			foreach ($module['menus']['link'] as $link) {
				if (!isset($menus[$link['type']])) {
					continue;
				}
				
				$menus[$link['type']][] = [
					'label' => $link['label'],
					'url'	=> $link['url']
				];
			}
		}
	
		$this->menus = $menus;
		//Cache::write("module_menus", $menus);
	}

	protected function __validateApi() {
		if ($this->hasParam('api_id') && $this->hasParam('api_key')) { 
			$user = User::find_by_api_id($this->params('api_id'));
		} else return;

		if (!isset($user))
			return;

		if (!$user->validateApiKey($this->params('api_key'))) 
			return;
		
		$this->user = $user;
	}
	
	protected function user() {
		if (isset($this->user)) 
			return $this->user;
		
		// First attempt to get user from session
		$user	= unserialize(base64_decode($this->session()->read('User')));
		if ($user) {
			$this->user = $user;
			return $this->user;
		} 
		
		return null;
	}
	
	protected function _checkPrivilege()
	{
		$method = $this->request()->method();
		$minPrivilege = SuperAdminPrivilege;
		if ($method == Request::GET) {
			$minPrivilege = $this->minReadPrivilege;
		} elseif ($method == Request::POST || $method == Request::PUT || $method == Request::DELETE) {
			$minPrivilege = $this->minWritePrivilege;
		}
	
		if (!$this->user() || !($this->user()->permissions & $minPrivilege)) {
			$this->redirectTo('/admin/signin', ['error' => 'You don\'t have permission to do this']);
		}
	}
	
}

?>
