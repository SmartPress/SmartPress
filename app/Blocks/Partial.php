<?php 
namespace Cms\Blocks;


use \Cms\Lib\Block\Base;
use \Speedy\View;

class Partial extends Base {
	
	public function render() {
		$view	= View::instance();
		$file	= $view->findFile($this->partial); 
		if ($file === false) return '';
		
		return $view->render($this->partial, [], $this->data());
	}
	
	public static function info() {
		return [
			'title'	=> 'Partial Block',
			'params'=> ['partial' => ['input' => 'textFieldTag']]
		];
	}
}

?>