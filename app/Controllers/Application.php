<?php
namespace Cms\Controllers;


use \Speedy\Controller;
use \Speedy\Session;

class Application extends Controller {

	public $layout	= "default"; 
	
	protected $beforeFilter	= ['__setBackUrl'];
	
	
	protected function __setBackUrl() {
		$ext = $this->params('ext');
		if (!isset($ext) || $ext != 'html')
			return; 
		
		$session = Session::instance();
		$previous_url	= $session->read('_back_url');
		if ($previous_url)
			$session->setData('back_url', $previous_url);
		$session->write('_back_url', $this->_request()->uri());
	}
	
}

?>