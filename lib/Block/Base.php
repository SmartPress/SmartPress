<?php 
namespace SmartPress\Lib\Block;


use \Speedy\Object;

abstract class Base extends Object {
	
	public function reset() {
		unset($this->_data);
		return $this;
	}
	
	public function setUp() {}
	
	abstract public function render();
	
	//abstract public static function info();
	
}

?>