<?php 
namespace SmartPress\Helpers;


use SmartPress\Lib\View\Helpers\Base;
use SmartPress\Models\Config\Manager as ConfigManager;
use Speedy\Utility\Inflector;
use Speedy\View;


class Pagination extends Base {

	const StartToken= "%start%";

	const EndToken 	= "%end%";

	const TotalToken= "%total%";
 
	const CounterFormatConfigName = "pagination/counter/format";

	const DefaultCounterFormat = "Showing %start%-%end% of %total% results.";


	public function pageCounter($format = null, $pagination = null) {
		if (!$format) {
			$format = $this->_counterFormat();
		}

		if (!$pagination && !($pagination = $this->_paginationVars()))
			return;

		extract($pagination);
		$text 	= str_replace(self::StartToken, $start, $format);
		$text	= str_replace(self::EndToken, $end, $text);
		$text	= str_replace(self::TotalToken, $total, $text);

		return $text;
	}

	private function _paginationVars() {
		$vars = $this->vars();
		if (isset($this->view()->pagination)) {
			$pagination = $this->view()->pagination;
		} elseif (isset($vars['pagination'])) {
			$pagination = $vars['pagination'];
		} else {
			return;
		}

		return $pagination;
	}

	private function _counterFormat() {
		if (ConfigManager::has(self::CounterFormatConfigName)) {
			$format = ConfigManager::get(self::CounterFormatConfigName);
		} else {
			$format = self::DefaultCounterFormat;
		}

		return $format;
	}
	
}
?>
