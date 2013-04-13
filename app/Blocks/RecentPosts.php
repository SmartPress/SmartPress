<?php 
namespace SmartPress\Blocks;


use SmartPress\Models\Post;
use Speedy\View;

class RecentPosts extends Partial {
	
	use \Speedy\View\Helpers\Html;
	
	
	/*public function addData($data) {
		parent::addData($data);
	}*/
	
	public function setUp() {
		$this->posts = Post::recent();
		//$this->setData('items', isset($items) ? $items->toList() : []);
	}
	
	public static function info() {
		return [
			'title'	=> 'Recent Posts',
			/*'params'=> [
				'menu' => [
					'input' => 'selectTag', 
					'label' => 'Menu Name', 
					'options'	=> Menu::instance()->optionsFromCollectionForSelect(MenuModel::treeForOptions(), 'id', 'title')
				],
				'partial'	=> [
					'input'	=> 'textFieldTag',
					'label'	=> 'Partial'
				]
			]*/
		];
	}
}

?>

