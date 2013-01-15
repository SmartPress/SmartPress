<?php 
namespace SmartPress\Helpers;

use SmartPress\Lib\View\Helpers\Base;
use SmartPress\Models\Config\Manager as ConfigManager;

class Title extends Base {

	public function title() {
		$vars	= $this->vars();
		$title	= (isset($vars['title_for_layout'])) ? $vars['title_for_layout'] : null;

		if ($title) {
			return $title;
		} elseif ($title = ConfigManager::get('title/default')) {
			return $title;
		}
	}
	
}
?>
