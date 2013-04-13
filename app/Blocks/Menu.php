<?php 
namespace SmartPress\Blocks;


use SmartPress\Models\Menu as MenuModel;
use Speedy\View;

class Menu extends Partial {
	
	use \Speedy\Traits\Singleton;
	use \Speedy\View\Helpers\Html;
	
	
	/*public function addData($data) {
		parent::addData($data);
	}*/
	
	public function setUp() {
		$items = MenuModel::itemsForId($this->menu);
		$this->setData('items', isset($items) ? $items->toList() : []);
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
