<?php 
namespace Cms\Lib\Concerns;


trait Tree {
	
	public $__treeSeparator;
	
	
	public static function formattedTree($separator = '-', $options = []) {
		$this->__treeSeparator	= $separator;
		$items	= self::all($options);
		
		$level = 1;
		$lastRight = null;
		foreach ($items as $item) {
			if ($lastRight) {
				
			}
			
			$this->title	= self::separatorLevel($level); 
		}
	}
	
	public static function separatorLevel($level) {
		$ret	= '';
		for ($i = 1; $i <= $level; $i++) {
			$ret	.= $this->__treeSeparator; 
		}
		
		return $ret;
	}
	
}
?>