<?php 
namespace SmartPress\Helpers;


use SmartPress\Lib\View\Helpers\Base;
use SmartPress\Models\Config\Manager as ConfigManager;
use SmartPress\Models\Config;
use Speedy\Utility\Inflector;

class Schema extends Base {

	use \Speedy\View\Helpers\Html {
		\Speedy\View\Helpers\Html::__construct as private __htmlConstruct;
	}


	public function __construct(&$view, $options = null) {
		parent::__construct($view, $options);

		$this->__htmlConstruct();
		return $this;
	}

	public function itemscope($scope = null) {
		if (!$scope) {
			$schema = isset($this->view()->schema) ? $this->view()->schema : [];
			$scope = isset($schema['scope']) ? $schema['scope'] : null;
		}

		return $scope ? " itemscope itemtype=\"http://schema.org/$scope\"" : '';
	}
	
}

