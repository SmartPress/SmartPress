<?php 
namespace SmartPress\Lib\Concerns;


trait Pagination {
	
	private static $_increment = 10;
	
	private static $_offset = 1;
	
	private static $_maxPages = 5;
	
	private static $__pagination = [];

	private static $_controller = null;

	private static $_linksHelper = null;

	
	
	public static function paginate($controller, $increment = null, $conditions = []) {
		self::$_linksHelper = \Speedy\Utility\Links::instance();
		self::setController($controller);
		if ($increment) self::setIncrement($increment);
		
		$pagination = self::__pageParams($controller->params('page'), $conditions);
		$sort = $controller->params('order');
		$sortType = $controller->params('order_type');

		if (!empty($sort)) {
			$sortType	= (empty($sortType)) ? ' asc' : ' desc';
			$conditions['order']	= $sort . $sortType;
		}

		$conditions['limit']	= $pagination['limit'];
		$conditions['offset']	= $pagination['offset'];
		return self::find('all', $conditions);
	}

	public static function setController(\Speedy\Controller $controller) {
		self::$_controller = $controller;
	}
	
	public static function setIncrement($increment) {
		static::$_increment = $increment;
	} 
	
	public static function setOffset($offset) {
		static::$_offset = $offset;
	}
	
	public static function paginationVars($name = null) {
		return ($name) ? self::$__pagination[$name] : self::$__pagination;
	}
	
	private static function setPaginationVar($name, $value) {
		self::$__pagination[$name]	= $value;
		return $value;
	}
	
	private static function __pageParams($page, $conditions = []) {
		$count = self::setPaginationVar('count', static::count($conditions));
		$page	= ($page) ? $page : 1;
		
		$maxPage	= ceil($count/self::$_increment);
		if ($maxPage < 1)
			$maxPage = 1;
		
		$offset = ((self::$_increment * $page) - self::$_increment) + 1;
		$nextPage	= (($nextPage = $page + 1) <= $maxPage) ? $nextPage : null;
		$prevPage	= (($prevPage = $page - 1) > 0) ? $prevPage : null;
		
		$lowerBound	= ceil(self::$_maxPages/2);
		$upperBound	= self::$_maxPages - $lowerBound;
		$upper = $page + $upperBound;
		while ($upper > $maxPage) {
			$upper--;
			$lowerBound++;
		}
		
		$lower = $page - $lowerBound;
		if ($lower < 1)
			$lower = 1;
		
		$targetDiff = $upper - $lower;
		while ($targetDiff < (self::$_maxPages - 1)) {
			$upper++;
			$targetDiff++;
		}
		
		while ($targetDiff > (self::$_maxPages - 1)) {
			$lower++;
			$targetDiff--;
		}

		self::setPaginationVar('maxPages', $maxPage);
		self::setPaginationVar('lower', $lower);
		self::setPaginationVar('upper', $upper);
		self::setPaginationVar('limit', self::$_increment);
		self::setPaginationVar('offset', $offset);
		self::setPaginationVar('nextPage', $nextPage);
		self::setPaginationVar('prevPage', $prevPage);
		self::setPaginationVar('currentPage', $page);
		//self::setPaginationVar($name, $value)
		
		return self::$__pagination; 
	}
	
}
?>
