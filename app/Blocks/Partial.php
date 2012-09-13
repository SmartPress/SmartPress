<?php 
namespace Cms\Blocks;


use \Cms\Lib\Block\Base;
use \Speedy\View;

class Partial extends Base {
	
	public function render() {
		$view	= View::instance();
		$file	= $view->findFile($this->partial);
		if ($file === false) return '';
		
		ob_start();
		$view->render($this->partial, [], $this->data());
		$content	= ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	public static function info() {
		return [
			'title'	=> 'Partial Block',
			'params'=> ['textFieldTag' => 'partial']
		];
	}
}

?>