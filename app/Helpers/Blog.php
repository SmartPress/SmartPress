<?php 
namespace SmartPress\Helpers;


use SmartPress\Lib\View\Helpers\Base;
use SmartPress\Models\Config\Manager as ConfigManager;
use SmartPress\Models\Config;
use SmartPress\Models\Menu;
use Speedy\Utility\Inflector;
use Speedy\View;

class Blog extends Base {

	use \Speedy\View\Helpers\Html {
		\Speedy\View\Helpers\Html::__construct as private __htmlConstruct;
	}


	private $_summarizeOptions = [
		'ending'	=> '...',
		'length'	=> 500,
		'acceptedTags'	=> ['a', 'br', 'p', 'h3', 'h2']
	];


	public function __construct(&$view, $options = null) {
		parent::__construct($view, $options);

		$this->__htmlConstruct();
		return $this;
	}


	public function title() {
		$vars	= $this->vars();
		$title	= (isset($vars['title_for_layout'])) ? $vars['title_for_layout'] : null;
		if (!$title)
			$title = $this->view()->title_for_layout;

		if (!empty($title)) {
			return $this->formatTitle($title);

		} elseif ($title = ConfigManager::get(Config::DefaultTitleName)) {
			return $title;
		}
	}

	private function formatTitle($title) {
		$format = ConfigManager::get(Config::TitleFormat);
		if (is_string($format) && strpos($format, '%title%') !== false) {
			$title = str_replace('%title%', $title, $format);

		}

		return $title;
	}

	public function tagLine() {
		$tagLine	= ConfigManager::get(Config::BlogTagLineName);
		return ($tagLine) ? $tagLine : '';
	}

	public function nav($name) {
		$inflected = Inflector::slugize($name);
		$partial = "shared/menus/_$inflected";
		$items = Menu::for_title($inflected);
		
		if (View::instance()->findFile($partial)) {
			return View::instance()->render($partial, [], ['items' => $items]);
		}

		return;
	}

	public function clean($str) {
		return htmlentities($str);
	}

	public function summarize($content, $link, $options = []) {
		$options = array_merge($this->_summarizeOptions, $options);
		extract($options);

		$acceptTags = '';
		foreach ($acceptedTags as $tag) {
			$acceptTags .= "<$tag>";
		}

		$content = strip_tags($content, $acceptTags);
		if (strlen($content) <= $length)
			return $content;

		$summary = $content;
		$offset = strpos($content, ' ');
		do {
			if ($offset === false) {
				break;
			}

			$nextOffset = strpos($content, ' ', $offset + 1);
			if ($nextOffset > $length) {
				$summary = substr($content, 0, $offset);
				break;
			}

			$offset = $nextOffset;
		} while (1);

		return substr($content, 0, $offset) . $ending . ' ' . $link;
	}

	public function meta_info() {
		$content = '';

		if (!isset($this->view()->meta)) {
			return $content;
		}

		$description = $this->view()->meta['description'];
		if (!empty($description))
			$content .= $this->meta('description', $description);

		$keywords = $this->view()->meta['keywords'];
		if (!empty($keywords))
			$content .= $this->meta('keywords', $keywords);

		$author = $this->view()->meta['author'];
		if (!empty($author))
			$content .= $this->meta('author', $author);

		return $content;
	}
	
}