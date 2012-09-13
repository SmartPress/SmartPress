<?php 
namespace Cms\Models\Block;


use \Cms\Models\Block;
use \Speedy\Cache;
use \Speedy\Singleton;

class Manager extends Singleton {
	
	use \Speedy\Utility\ArrayAccess;
	
	private $_blocks;
	
	
	
	public function __construct() {
		$this->__aaSetDelimeter('/');
		return $this;
	}
	
	public static function currentFor($controller, $action) {
		return self::instance()->_currentFor($controller, $action);
	}
	
	public static function &all() {
		return self::instance()->_all();
	}
	
	public function _currentFor($controller, $action) {
		$blocks	=& $this->_all();
		return $this->__dotAccess(['global', $controller, "$controller/$action"], $blocks);
	}
	
	public function &_all() {
		if (!empty($this->_blocks)) return $this->_blocks;
	
		$blocks	= Cache::read(Block::CacheName);
		if (empty($blocks)) {
			$blocks	= Block::all(['select' => 'id, path, block, element, params', 'order' => 'priority DESC']);
				
			$refined = [];
			foreach ($blocks as $block) {
				$refined[] = [
					'path'	=> $block->path,
					'data'	=> [
						'block'	=> $block->block,
						'element'	=> $block->element,
						'params'	=> $block->params,
						'path'		=> $block->path,
						'id'	=> $block->id
					]
				];
			}
			$blocks = $this->mutateDataWithKeyValue($refined, 'path', 'data');
			Cache::write(Block::CacheName, $blocks);
		}
	
		$this->_blocks = $blocks;
		return $this->_blocks;
	}
	
}

?>