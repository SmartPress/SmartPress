<?php 
namespace Cms\Lib\Concerns;


trait Tree {
	
	public static $__treeSeparator = '-';
	public static $__treeLft	= 'lft';
	public static $__treeRght	= 'rght';
	
	
	
	public function __construct(array $attributes=array(), $guard_attributes=true, $instantiating_via_find=false, $new_record=true) {
		parent::__construct($attributes, $gaurd_attributes, $instantiating_via_find, $new_record);
		
		debug('tmp');
	}
	
	public static function tree($separator = '-', $options = [], $lft = null, $rght = null) {
		if ($lft) self::$__treeLft	= $lft;
		if ($rght)self::$__treeRght = $rght;
		
		self::$__treeSeparator	= $separator;
		$options['order'] = self::$__treeLft . ' ASC';
		$items	= self::all($options);
		
		$level = 0;
		$lastItem = null;
		foreach ($items as &$item) {
			if (isset($lastItem) && isset($lastItem->{self::$__treeLft}) && isset($lastItem->{self::$__treeRght})) {
				if ($item->{self::$__treeLft} == ($lastItem->{self::$__treeRght} + 1) && 
					($lastItem->{self::$__treeRght} - $lastItem->{self::$__treeLft}) > 1) {
					$level++;
				} elseif (($item->{self::$__treeLft} - $lastItem->{self::$__treeRght}) > 1) {
					$level--;
				}
			}
			$item->title= self::separatorLevel($level) . $item->title;
			 
			$lastItem	= $item;
		}
		
		return $items;
	}
	
	public static function separatorLevel($level) {
		if (empty($level)) return '';
		
		$ret	= '';
		for ($i = 1; $i <= $level; $i++) {
			$ret	.= $this->__treeSeparator; 
		}
		
		return $ret;
	}
	
	
	
}
?>