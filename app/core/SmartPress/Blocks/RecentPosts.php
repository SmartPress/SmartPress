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
		$this->partial = !empty($this->partial) ? $this->partial : 'posts/_recent_posts';
		\Speedy\Logger::debug($this->posts);
		//$this->setData('items', isset($items) ? $items->toList() : []);
	}
	
	public static function info() {
		return [
			'title'	=> 'Recent Posts',
				'partial'	=> [
					'input'	=> 'textFieldTag',
					'label'	=> 'Partial'
				]
			];
	}
}

?>

