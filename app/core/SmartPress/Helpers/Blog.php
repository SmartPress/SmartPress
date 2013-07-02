<?php 
namespace SmartPress\Helpers;


use SmartPress\Lib\View\Helpers\Base;
use SmartPress\Models\Config\Manager as ConfigManager;
use SmartPress\Models\Config;
use SmartPress\Models\Menu;
use Speedy\Utility\Inflector;
use Speedy\View;
use DOMDocument;

class Blog extends Base {

	use \Speedy\View\Helpers\Html {
		\Speedy\View\Helpers\Html::__construct as private __htmlConstruct;
	}

	private $_excerptOptions = [
		'length'	=> 500,
		'acceptedTags'	=> ['a', 'br', 'p', 'h3', 'h2', 'img', 'div']
	];

	private $_summarizeOptions = [
		'ending'	=> '... ',
		'length'	=> 300
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
		$content = strip_tags($content);
		extract($options);

		if (strlen($content) <= $length) 
			return $content;

		$offset = strpos($content, ' ');
		while (($nextOffset = strpos($content, ' ', $offset + 1)) <= $length) {
			$offset = $nextOffset;
		}

		return substr($content, 0, $offset) . $ending . $link;
	}

	public function excerpt($content, $link, $options = []) {
		if (empty($content) || strlen($content) < 1)
			return '';

		$options = array_merge($this->_excerptOptions, $options);
		extract($options);

		$acceptTags = '';
		foreach ($acceptedTags as $tag) {
			$acceptTags .= "<$tag>";
		}

		$content = strip_tags($content, $acceptTags);
		if (strlen(strip_tags($content)) <= $length)
			return $content;

		$summary = '';
		$dom = new DOMDocument();
		if (!$dom->loadHTML($content)) {
			return '';
		}

		$dom->removeChild($dom->firstChild);
		$element = $dom->firstChild->firstChild->firstChild;
		do {
			$summary .= $dom->saveHTML($element);

			$element = $element->nextSibling;			
		} while (strlen($summary) < $length && $element);

		return $summary . ' ' . $link;
	}

	public function meta_info() {
		$content = '';

		if (!isset($this->view()->meta)) {
			return $content;
		}

		$meta = $this->view()->meta;
		if (!is_array($meta))
			return $content;

		extract($meta);
		if (!empty($description))
			$content .= $this->meta('description', $description);

		if (!empty($keywords))
			$content .= $this->meta('keywords', $keywords);

		if (!empty($author))
			$content .= $this->meta('author', $author);

		return $content;
	}

	public function google_analytics() {
		return html_entity_decode(ConfigManager::get(Config::GACode));
	}
	
}

