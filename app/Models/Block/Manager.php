<?php 
namespace Cms\Models\Block;


use \Cms\Models\Block;
use \Speedy\Cache;
use \Speedy\Loader;
use \Speedy\Singleton;
use \Speedy\Request;
use \Speedy\Utility\Inflector;

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
	
	public static function availableBlocks() {
		$namespaces	= Loader::instance()->namespaces();
		
		$blocks	= ['' => 'Select One'];
		foreach ($namespaces as $ns => $nsDirs) {
			if (!is_array($nsDirs)) $nsDirs	= [$nsDirs];
			foreach ($nsDirs as $dir) {
				$blockPath	= $dir . DS . 'Blocks';
				
				foreach (rglob($blockPath . DS . '*.php') as $blockDir) {
					$relPath	= str_replace($blockPath, '', $blockDir);
					$relPath	= str_replace('.php', '', $relPath);
					$aRelPath	= explode(DS, $relPath);
					
					array_walk($aRelPath, function(&$item, $key) {
						$item	= Inflector::underscore($item);
					});
					
					$blockNs	= $ns . '.blocks' . implode('.', $aRelPath);
					$class	= Loader::instance()->toClass($blockNs);
					if (!class_exists($class)) {
						continue;
					}
					
					$info	= $class::info();
					$blocks[$blockNs] = $info['title'];
				}
			}
		}
		
		return $blocks;
	}
	
	public static function scopes($controller = null, $action = null) {
		$params	= Request::instance()->params();
		if (!$controller)
			$controller	= implode('/', $params['controller']);
		if (!$action)
			$action	= $params['action'];
		
		return [
			'global'	=> 'Global',
			$controller	=> 'Controller',
			"$controller/$action"	=> 'Action Only'
		];
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