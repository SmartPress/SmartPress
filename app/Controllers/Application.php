<?php
namespace SmartPress\Controllers;


use \Speedy\Controller;
use \Speedy\Session;

class Application extends Controller {

	public $layout	= "default"; 
	
	protected $beforeFilter	= ['__setBackUrl'];
	
	
	protected function __setBackUrl() {
		$ext = $this->params('ext');
		if (!isset($ext) || $ext != 'html')
			return; 
		
		//$session = Session::instance();
		$previous_url	= Session::read('_back_url');
		if ($previous_url)
			Session::write('back_url', $previous_url);
		Session::write('_back_url', $this->_request()->uri());
	}
	
}

?>
