<?php 
namespace Cms\Blocks;


use \Cms\Models\Menu as MenuModel;
use \Speedy\View;

class Menu extends Partial {
	
	use \Speedy\Traits\Singleton;
	use \Speedy\View\Helpers\Html;
	
	
	public function setUp() {
		$this->setData('items', MenuModel::itemsForId($this->menu));
	}
	
	public static function info() {
		return [
			'title'	=> 'Menu',
			'params'=> [
				'menu' => [
					'input' => 'selectTag', 
					'label' => 'Menu Name', 
					'options'	=> Menu::instance()->optionsFromCollectionForSelect(MenuModel::treeForOptions(), 'id', 'title')
				],
				'partial'	=> [
					'input'	=> 'textFieldTag',
					'label'	=> 'Partial'
				]
			]
		];
	}
}

?>