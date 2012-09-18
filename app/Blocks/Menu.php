<?php 
namespace Cms\Blocks;


use \Cms\Lib\Block\Base;
use \Cms\Models\Menu as MenuModel;
use \Speedy\View;

class Menu extends Base {
	
	public function render() {
		
	}
	
	public static function info() {
		return [
			'title'	=> 'Menu',
			'params'=> ['textFieldTag' => 'partial']
		];
	}
}

?>