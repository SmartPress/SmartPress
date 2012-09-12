<?php 
namespace Cms\Helpers;


use \Cms\Lib\View\Helpers\Base;
use \Cms\Models\Event\Manager as EventManager;
use \Cms\Models\Block as BlockModel;
use \Speedy\Cache;
use \Speedy\Loader;
use \Speedy\View;

class Block extends Base {
	
	use \Speedy\Utility\ArrayAccess;
	
	private $_blocks;
	
	private $_widgets	= array();

	
	
	public function __construct(&$view, $options = null) {
		parent::__construct($view, $options);
		
		$this->__aaSetDelimeter('/');
		$this->loadBlocks();
		
		return $this;
	}
	
	public function block($name, $options = []) {
		if (!$this->_blocks) $this->loadBlocks();
		
		$controller	= implode('/', $this->view()->param('controller'));
		$action		= $this->view()->param('action');
		$blocks = $this->__dotAccess(['global', $controller, "$controller/$action"], $this->_blocks);

		if (empty($blocks)) return;
		
		$controller	= implode('_', $this->view()->param('controller'));
		EventManager::dispatch("pre_load_block_$name", $blocks);
		EventManager::dispatch("pre_load_block_{$name}_{$controller}", $blocks);
		EventManager::dispatch("pre_load_block_{$name}_{$controller}_{$action}", $blocks);
		
		output($this->view()->params);
		foreach ($blocks as $block) {
			if ($block['block'] != $name) continue;
			
			$params	= unserialize($block['params']);
			$params = (!$params || $params === null) ? array() : $params;
			if (isset($options[$block['element']])) $params = array_merge($params, $options[$block['element']]);
			
			if (isset($params['exclusions'])) {
				if (is_array($params['exclusions']) && in_array($this->view()->here(), $params['exclusions'])) {
					continue;
				} elseif (is_string($params['exclusions']) && $params['exclusions'] == $this->view()->here()) {
					continue;
				}
			}
			
			if (isset($params['only'])) {
				if (is_array($params['only']) && !in_array($this->view()->here(), $params['only'])) {
					continue;
				} elseif (is_string($params['only']) && $params['only'] != $this->view()->here()) {
					continue;
				}
			}
			
			if ($this->widgetExists($block['element'])) {
				echo $this->renderWidget($block['element'], $params);
			} elseif ($this->view()->isPartial($block['element'])) {
				$params = array_merge($params, $this->view()->vars());
				$this->view()->render($block['element'], $params);
			}
		}
		
		EventManager::dispatch("post_load_block_$name", $blocks);
		EventManager::dispatch("post_load_block_{$name}_{$controller}", $blocks);
		EventManager::dispatch("post_load_block_{$name}_{$controller}_{$action}", $blocks);
	}
	
	public function blocks() {
		return $this->_blocks;
	}
	
	private function loadBlocks() {
		if (!empty($this->_blocks)) return $this;
		
		$blocks	= Cache::read(BlockModel::CacheName);
		if (empty($blocks)) {
			$blocks	= BlockModel::all(['select' => 'path, block, element, params', 'order' => 'priority DESC']);
			
			$refined = [];
			foreach ($blocks as $block) {
				$refined[] = [
					'path'	=> $block->path,
					'data'	=> [
						'block'	=> $block->block,
						'element'	=> $block->element,
						'params'	=> $block->params
					]
				];
			}
			$blocks = $this->mutateDataWithKeyValue($refined, 'path', 'data');
			Cache::write(BlockModel::CacheName, $blocks);
		}
		
		$this->_blocks = $blocks;
		return $this;
	}
	
	private function pushWidget($widget, $obj) {
		$this->_widgets[$widget]	= $obj;
		return $this;
	}
	
	private function widgetRegistered($widget) {
		return (isset($this->_widgets[$widget]));
	}
	
	private function widgetExists($widget) {
		if ($this->widgetRegistered($widget) || import($widget)) return true;
	
		return false;
	}
	
	private function renderWidget($widget, $params	= array()) {
		if (!$this->widgetExists($widget)) {
			return '';
		}
	
		$widgetObj	= $this->widget($widget);
		$widgetObj->reset()->setParams($params)->setUp();
		return $widgetObj->render() . "\n";
	}
	
	private function widget($widget) {
		if ($this->widgetRegistered($widget)) {
			return $this->_widgets[$widget];
		}
	
		$widgetClass= Loader::instance()->toClass($widget);
		$widgetObj	= new $widgetClass();
		$this->pushWidget($widget, $widgetObj);
	
		return $widgetObj;
	}
	
}
?>