<?php 
namespace Cms\Lib\View\Helpers;

use \Speedy\View\Helpers\Base as HelperBase;

class Base extends HelperBase {
	
	/**
	 * Parent view object
	 * @var \Speedy\View\Base
	 */
	private $_view;
	
	/**
	 * @var array
	 */
	protected $_options;
	
	
	
	public function __construct(&$view, $options = null) {
		$this->setView($view)->setOptions($options);
		
		return $this;
	}
	
	/**
	 * Set parent view object
	 * @param \Speedy\View\Base $view
	 */
	protected function setView(&$view) {
		$this->_view =& $view;
		return $this;
	}
	
	/**
	 * Setter for options
	 * @param array $options
	 */
	protected function setOptions($options) {
		$this->_options = $options;
		return $this;
	}

	/**
	 * Getter for vars
	 * @return array
	 */
	protected function vars() {
		return $this->_view->vars();
	}
	
	/**
	 * Getter for view
	 * @return object \Speedy\View\Base
	 */
	protected function view() {
		return $this->_view;
	}
	
}
?>