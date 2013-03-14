<?php
namespace SmartPress\Models;


use Speedy\Cache;
use Speedy\Set;

class Menu extends \Speedy\Model\ActiveRecord {
	
	use \SmartPress\Lib\Concerns\Tree;
	
	public $children;

	public static $_all;

	const AllCacheName = "menus";

	public static $after_save = ['clearCaches'];
	public static $after_update = ['clearCaches'];
	public static $before_destroy = ['clearCaches'];
	
	

	public function clearCaches() {
		Cache::instance()->flush(TMP_PATH . DS . 'cache' . DS . self::AllCacheName);
	}
	
	public function set_title($title) {
		return $this->assign_attribute('title', htmlentities($title));
	}
	
	public function items() {
		$items = $all	= self::childrenFor($this->lft, $this->rght);
		return $items;
	}

	public static function for_title($title) {
		$cacheName = 'menus' . DS . $title;
		$menu = Cache::read($cacheName);
		if (empty($menu)) {
			$menu = self::all([
					'conditions' => [
						'p.title = ? AND (m.lft > p.lft AND m.lft < p.rght)',
						$title
					],
					'from'	=> 'menus AS m, menus AS p',
					'order' => 'm.lft ASC',
					'select'=> 'm.*'
				]);
			Cache::write($cacheName, $menu);
		}

		return $menu;
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
	
	public static function treeForOptions() {
		$menus	= (array)self::tree();
		array_unshift($menus, new Menu(['title' => 'Select One']));
		return $menus;
	}
	
	public static function itemsForId($id) {
		$items	= Cache::read("menu_$id");
		if (empty($items)) {
			$items	= self::allChildrenById($id);
			Cache::write("menu_$id", (array)$items);
		}
		
		return $items;
	}
	
}

?>
