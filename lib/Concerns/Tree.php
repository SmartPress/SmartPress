<?php 
namespace Cms\Lib\Concerns;


use \Speedy\ActiveRecord\Relationships\BelongsTo;
use \Speedy\ActiveRecord\Relationships\HasMany;

trait Tree {
	
	public static $__treeSeparator = '-';
	public static $__treeLft	= 'lft';
	public static $__treeRght	= 'rght';
	
	
	
	public function _construct() {
	
		static::table()->callback->register('before_create', 'setTreeLocation');
		static::table()->callback->register('after_destroy', 'reindexTreeAfterDelete');
		if (!isset(static::$belongs_to)) {
			static::table()->add_relationship(new BelongsTo([
					'parent', 
					'class_name' => get_class($this)
					]));
		}
		
		if (!isset(static::$has_many)) {
			static::table()->add_relationship(new HasMany([
					'children', 
					'foreign_key' => 'parent_id', 
					'class_name' => get_class($this), 
					'order' => 'title ASC'
					]));
		}
	}
	
	public function setTreeParent() {
		$maxValue	= static::maximum(static::$__treeRght);
		
		$upper;
		if (!empty($maxValue)) {
			$upper	= static::where([static::$__treeRght => $maxValue])->first();
		}
		
		if (empty($upper)) $rght	= 0;
		else $rght = $upper;
		
		$this->{static::$__treeLft} = $rght + 1;
		$this->{static::$__treeRght}= $this->{static::$__treeLft} + 1;
		
		return true;
	}
	
	public function setTreeChild() {
		$parent	= static::find($this->parent_id);
		if (empty($parent)) return false;
		
		$this->{static::$__treeLft}	= $parent->{static::$__treeRght};
		$this->{static::$__treeRght}= $parent->{static::$__treeRght} + 1;
		
		static::update_all(static::$__treeRght . ' = ' . static::$__treeRght . ' + 2', [static::$__treeRght . ' >= ?', $this->{static::$__treeLft}]);
		static::update_all(static::$__treeLft . ' = ' . static::$__treeLft . ' + 2', [static::$__treeLft . ' > ?', $this->{static::$__treeLft}]);
		
		return true;
	}
	
	public function setTreeLocation() {
		$current	= ($this->id) ? self::find($this->id) : null;
		// Return true if the parent id hasn't changed
		if (!empty($current) && $current->parent_id == $this->parent_id) return true;
		
		if (empty($this->parent_id) || $this->parent_id < 1) {
			return $this->setTreeParent();
		} else {
			return $this->setTreeChild();
		}
	}
	
	public function reindexTreeAfterDelete() {
		$children	= static::allChildrenOf($this); 

		if (count($children) > 0) {
			$childrenIds= [];
			foreach ($children as $child) {
				$childrenIds[]	= $child->id;
			} 

			$options	= ['conditions' => ['id IN (?)', $childrenIds]];
			static::delete_all($options);
		}
		
		$shift	= 1 + ($this->{static::$__treeRght} - $this->{static::$__treeLft});
		static::update_all(static::$__treeRght . ' = ' . static::$__treeRght . " - '$shift'", [static::$__treeRght . ' >= ?', $this->{static::$__treeLft}]);
		static::update_all(static::$__treeLft . ' = ' . static::$__treeLft . " - '$shift'", [static::$__treeLft . ' > ?', $this->{static::$__treeLft}]);
		return true;
	}
	
	public static function tree($separator = '-', $options = [], $lft = null, $rght = null) {
		if ($lft) static::$__treeLft	= $lft;
		if ($rght)static::$__treeRght = $rght;
		
		static::$__treeSeparator	= $separator;
		$options['order'] = static::$__treeLft . ' ASC';
		$items	= static::all($options);
		
		$level = 0;
		$lastItem = null;
		foreach ($items as &$item) {
			if (isset($lastItem) && isset($lastItem->{static::$__treeLft}) && isset($lastItem->{static::$__treeRght})) {
				if ($item->{static::$__treeLft} == ($lastItem->{static::$__treeLft} + 1) && 
					($lastItem->{static::$__treeRght} - $lastItem->{static::$__treeLft}) > 1) {
					$level++;
				} elseif (($item->{static::$__treeLft} - $lastItem->{static::$__treeRght}) > 1) {
					$level--;
				}
			}
			output($level);
			$item->title= static::separatorLevel($level) . $item->title;
			 
			$lastItem	= $item;
		}
		
		return $items;
	}
	
	public static function allChildrenById($id) {
		$parent	= static::find($id);
		return static::allChildrenOf($parent);
	}
	
	public static function allChildrenOf($parent) {
		return static::all([
					'conditions' => [
						static::$__treeLft . ' BETWEEN ? AND ?',
						$parent->{static::$__treeLft},
						$parent->{static::$__treeRght}
					]
				]);
		
	}
	
	public static function separatorLevel($level) {
		if (empty($level)) return '';
		
		$ret	= '';
		for ($i = 1; $i <= $level; $i++) {
			$ret	.= self::$__treeSeparator; 
		}
		
		return $ret . ' ';
	}
	
}
?>