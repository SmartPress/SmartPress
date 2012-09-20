<?php
namespace Cms\Models;


use Speedy\Model\ActiveRecord\Base;
use Speedy\Set;

class Menu extends Base {
	
	use \Cms\Lib\Concerns\Tree;
	
	public $children;
	
	
	
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
		$menus	= self::all([
					'conditions' => ['parent_id = 0'],
					'order'	=> 'lft ASC'
				]);
		if ($menus->count() < 1) return $menus;
		
		$children = self::all([
					'conditions' => ['parent_id > 0'],
					'order'	=> 'lft ASC'
				]);
		if ($children->count() < 1) return $menus;
		
		foreach ($menus as &$menu) {
			$menu = self::addChildrenFromCollection($menu, $children);
		}
		
		return $menus;
	}
	
	public static function addChildrenFromCollection($menu, $children) {
		if ($children->count() < 1) return $menu;
		if (!isset($menu->children)) 
			$menu->children = new Set();
	
		
		foreach ($children as &$child) {
			if ($child->id == $menu->id) {
				continue;
			}
			
			if (($child->rght - $child->lft) > 1) {
				$child = self::addChildrenFromCollection($child, $children);
			}
			
			if ($child->parent_id != $menu->id) {
				continue;
			}
			
			if ($child->lft > $menu->rght) {
				continue;
			}
			
			$menu->children[] = $child;
		}
		
		return $menu;
	}
	
}

?>