<?php
namespace SmartPress\Lib\Settings;


use \Speedy\Object;

class Base extends Object {
	//public $uses	= array();
	//public $Acl	= null;
	
	protected $_config	= null;
	protected $_site	= array();
	protected $_errors	= array();
	
	
	public function __construct($config = null) {
		$this->_config	= $config;
		
		/*
		$site	= ClassRegistry::getObject('Site');
		$this->_site	= $site->current['Site'];
		
		foreach ($this->uses as $module) {
			Sys::import("Model.$module");
			$this->{$module}	= new $module;
		}
		
		Sys::import('Component.Acl');
		$this->Acl	= new AclComponent();
		$controller	= null;
		$this->Acl->startup($controller);
		
		return $this->_construct();
		*/
	}
	
	public function features() {
		return $this->_config->features->feature;
	}
	
	public function version() {
		return $this->_config->version;
	}
	
	public function formattedFeatures() {
		$return	= '';
		$i	= 1;
		foreach ($this->_config->features->feature as $feature) {
			$return .= (count($this->_config->features->feature) > $i) ? $feature . ', ' : $feature;
			$i++; 
		}
		
		return $return;
	}
	
	public function lastError() {
		return end($this->_errors);
	}
	
	public function config() {
		return $this->_config;
	}

	public function install() {
		return true;
	}
	
	public function uninstall() {
		return true;
	}
	
	public function update($version) {
		return true;
	}
	
	public function menu() {
		return array();
	}
	
	/**
	 * Setter for config
	 * @param object $config
	 * @return object $this
	 */
	protected function setConfig($config) {
		$this->_config = $config;
		return $this;
	}
	
	protected function addError($msg) {
		$this->_errors[]	= $msg;
		return $this;
	}
	
	protected function _parseIdentifier($identifier) {
		if (preg_match('/^([\w]+)\.(.*)$/', $identifier, $matches)) {
			return array(
				'model' => $matches[1],
				'foreign_key' => $matches[2],
			);
		}
		return $identifier;
	}
	
	/*function _getNodeId($class, $identifier) {
		$node = $this->Acl->{$class}->node($identifier);
		if (empty($node)) {
			if (is_array($identifier)) {
				$identifier = var_export($identifier, true);
			}
			$this->_addError(sprintf(__('Could not find node using reference "%s"', true), $identifier));
		}
		return Set::extract($node, "0.{$class}.id");
	}
	
	public function createAco($parent, $child) {
		return $this->_create('aco', $parent, $child);
	}
	
	public function createAro($parent, $child) {
		return $this->_create('aro', $parent, $child);
	}
	
	public function createArray($type, $arr) {
		if (!is_array($arr)) return false;
		
		foreach ($arr as $key => $val) {
			if (is_array($val)) {
				if (array_values($val) !== $val) {
					$keys	= array_keys($val);
					foreach ($keys as $child) {
						if (!$this->_create($type, $key, $child)) return false;
					}
					
					if (!$this->createArray($type, $val)) return false;
				} else {
					foreach ($val as $child) {
						if (!$this->_create($type, $key, $child)) return false;
					}
				}
			}
			else {
				if (!$this->_create($type, $key, $val)) return false;
			}
		}
		
		return true;
	}
	
	public function setTablePrefix($prefix) {
		$this->Acl->Aco->setTablePrefix($prefix);
		
		return $this;
	}
	
	protected function _create($type, $parent, $child) {
		if (!$type) {
			$this->_addError('Type not recogized.');
			return false;
		}
		elseif (strtolower($type) != 'aro' && strtolower($type) != 'aco') {
			$this->_addError('Type not recogized.');
			return false;
		}
		
		$class = ucfirst($type);
		$parent = $this->_parseIdentifier($parent);

		if (!empty($parent) && $parent != '/' && $parent != 'root') {
			$parent = $this->_getNodeId($class, $parent);
		} else {
			$parent = null;
		}

		$data = $this->_parseIdentifier($child);
		if (is_string($data) && $data != '/') {
			$data = array('alias' => $data);
		} elseif (is_string($data)) {
			$this->_addError('can not be used as an alias! is the root, please supply a sub alias');
			return false;
		}

		$data['parent_id'] = $parent;
		$this->Acl->{$class}->create();
		if ($this->Acl->{$class}->save($data)) {
			return true;
		} else {
			$this->_addError(sprintf(__("There was a problem creating a new %s '%s'.", true), $class, $child));
			return false;
		}
		
		$this->_addError('Unknown ACL create error');
		return false;
	}

	/**
	 * @param $aro string request object
	 * @param $aco string controlled object
	 * 
	 * Grant permission for a requester on an object
	 */
	/*public function grant($aro, $aco) {
		return $this->Acl->grant($aro, $aco);
	}*/
}

?>
