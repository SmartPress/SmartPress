<?php 
namespace Cms\Helpers;


use \Cms\Lib\View\Helpers\Base;
use \Cms\Models\Block\Manager as BlockManager;
use \Cms\Models\Event\Manager as EventManager;
use \Speedy\Cache;
use \Speedy\Loader;
use \Speedy\View;

class Block extends Base {
	
	private $_widgets	= array();

	
	
	public function __construct(&$view, $options = null) {
		parent::__construct($view, $options);
		
		return $this;
	}
	
	public function block($name, $options = []) {
		$controller	= implode('/', $this->view()->param('controller'));
		$action		= $this->view()->param('action');
		$blocks	= BlockManager::currentFor($controller, $action);

		if (empty($blocks)) return;
		
		$controller	= implode('_', $this->view()->param('controller'));
		EventManager::dispatch("pre_load_block_$name", $blocks);
		EventManager::dispatch("pre_load_block_{$name}_{$controller}", $blocks);
		EventManager::dispatch("pre_load_block_{$name}_{$controller}_{$action}", $blocks);
		
		output($this->view()->params);
		foreach ($blocks as $block) {
			if ($block['block'] != $name) continue;
			
			//$params	= json_decode($block['params'], true);
			$params = (!$params || $params === null) ? array() : $block['params'];
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
			} 
		}
		
		EventManager::dispatch("post_load_block_$name", $blocks);
		EventManager::dispatch("post_load_block_{$name}_{$controller}", $blocks);
		EventManager::dispatch("post_load_block_{$name}_{$controller}_{$action}", $blocks);
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
		$widgetObj->reset()->setData($params)->setUp();
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