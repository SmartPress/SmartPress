<?php
namespace SmartPress\Lib\Router\Routes;

use \Speedy\Router\Routes\Match;
use \Speedy\Cache;
use \SmartPress\Models\Post;

class Slug extends Match {

	public $name	= "page_slugs";

	public $type	= 1;
	
	private $_slugs;
	
	

	/**
	 * Slug match router
	 * @param string $format
	 * @param array $options
	 */
	public function __construct($params = array()) {
		parent::__construct($params);
		
		if (isset($params['type'])) 
			$this->setType($params['type']); 
			
		$this->setName("{$params['type']}_slugs");
	}

	public function match($request) {
		if (!$this->compile($request)) return false;
		
		$params	= $this->params();
		$slugs	= $this->slugs(); 
		
		if (!isset($slugs[$params['slug']])) return false;
		$this->addParam('id', $slugs[$params['slug']]);
		
		return true;
	}
	
	/**
	 * Getter for type
	 * @return string
	 */
	public function type() {
		return $this->type;
	}
	
	/**
	 * Getter for name
	 * @return string
	 */
	public function name() {
		return $this->name;
	}
	
	/**
	 * Getter for slugs
	 * @return array
	 */
	protected function slugs() {
		if (!$this->_slugs) {
			$this->_slugs	= $this->cachedSlugs();
		}
		
		return $this->_slugs;
	}
	
	/**
	 * Getter for cached slugs
	 * @return array
	 */
	protected function cachedSlugs() {
		$slugs	= Cache::read($this->name());
		if (empty($slugs)) {
			$data	= Post::all(array(
				'conditions'=> array('type' => $this->type()),
				'select'	=> 'id, slug'
			));
			
			if (empty($data)) return null;	
			
			$slugs	= array();
			foreach ($data as $model) {
				if (empty($model->slug)) {
					continue;
				}
				
				$slugs[$model->slug]	= $model->id;
			}
			
			Cache::write($this->name(), $slugs);
		}
		
		return $slugs;
	}
	
	/**
	 * Setter for cache name
	 * @param string $name
	 * @return \SmartPress\Router\Routes\Slug
	 */
	protected function setName($name) {
		$this->name	= $name;
		return $this;
	}
	
	/**
	 * Setter for type
	 * @param integer $type
	 * @return \SmartPress\Router\Routes\Slug
	 */
	private function setType($type) {
		$this->type	= $type;
		return $this;
	}

}
?>
