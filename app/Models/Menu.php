<?php
namespace Cms\Models;


use \Speedy\Model\ActiveRecord\Base;

class Menu extends Base {
	
	use \Cms\Lib\Concerns\Tree {
		\Cms\Lib\Concerns\Tree::__construct insteadof self;
	}
	
	
	
	public function items() {
		$items = $all	= self::childrenFor($this->lft, $this->rght);
		return $items;
	}
	
	public static function childrenFor($lft, $rght) {
		return self::all([
					'conditions' => [
						'lft > ?'	=> $lft,
						'rght < ?'	=> $rght
					]
				]);
	}
	
	public static function allMenus() {
		return self::all([
					'conditions' => ['parent_id = 0']
				]);
	}
	
}

?>