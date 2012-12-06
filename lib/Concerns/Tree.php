<?php 
namespace Cms\Lib\Concerns;


use Cms\Lib\Concerns\Exceptions\TreeException;
use ActiveRecord\Relationships\BelongsTo;
use ActiveRecord\Relationships\HasMany;


trait Tree {
	
	private static $MoveDirectionUp	= 1;
	private static $MoveDirectionDown	= 2;
	
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
	
	public static function move($id, $offset = 1) {
		if (!is_int($offset)) $offset = intval($offset);
		if ($offset == 0) throw new TreeException('Offset is zero');
		
		$dir	= ($offset > 0) ? self::$MoveDirectionDown : self::$MoveDirectionUp; 
		$symbol	= ($dir === self::$MoveDirectionUp) ? '+' : '-';
		$node		= self::find($id);
		$children	= self::allChildrenOf($node);
		$siblings	= self::find_all_by_parent_id($node->parent_id, ['order' => 'lft ASC']);
		
		if ($siblings->count() <= 1) 
			throw new TreeException('No siblings found for ' . $node->parent_id);
		
		$nodeIndex	= null;
		foreach ($siblings as $index => $sibling) {
			if ($sibling->id == $node->id) {
				$nodeIndex	= $index;
				break;
			}	
		}
		
		$toIndex	= $nodeIndex + $offset;
		if ($toIndex < 0) 
			throw new TreeException('Index of node moving to not found, tried ' . $toIndex);
		
		$nodeSpan	= $node->{static::$__treeRght} - $node->{static::$__treeLft};
		$moveNode	= $siblings[$toIndex];
		$moveSpan	= $nodeSpan + 1; output("Move Span $moveSpan");
		
		$oldLft	= $node->{static::$__treeLft};
		$oldRght= $node->{static::$__treeRght};
		
		$newLft	= $moveNode->{static::$__treeLft};
		$newRght= $newLft + $nodeSpan;
		
		
		if ($dir === self::$MoveDirectionDown) {
			$conditions = [
				static::$__treeLft . ' > ? AND ' . static::$__treeRght . ' <= ?',
				$node->{static::$__treeRght},
				$moveNode->{static::$__treeRght}
			];
		} elseif ($dir === self::$MoveDirectionUp) {
			$conditions = [
				static::$__treeLft . ' >= ? AND ' . static::$__treeRght . ' < ?',
				$moveNode->{static::$__treeLft},
				$node->{static::$__treeLft}
			];
		}
		output($conditions);
		// Move affected siblings
		static::update_all(
				static::$__treeLft . ' = ' . static::$__treeLft . " $symbol $moveSpan, " .
				static::$__treeRght . ' = ' . static::$__treeRght . " $symbol $moveSpan",
				$conditions);
		output("Last Query " . static::connection()->last_query);
		output($conditions);
				
		
		// Figure out the span for the move
		if ($dir === self::$MoveDirectionUp)
			$moveSpan	= $node->{static::$__treeLft} - $moveNode->{static::$__treeLft};
		elseif ($dir === self::$MoveDirectionDown)
			$moveSpan	= $moveNode->{static::$__treeLft} - $node->{static::$__treeLft};
		
		// Move the node
		$node->{static::$__treeLft}	= $newLft;
		$node->{static::$__treeRght}= $newRght;
		if(!$node->save()) 
			throw new TreeException('Unable to save the moving node');
		
		$symbol	= ($dir === self::$MoveDirectionUp) ? '-' : '+';
		// Move any children
		$childrenIds	= [];
		if (count($children) < 1) return true;
		foreach ($children as $child) {
			$childrenIds[]	= $child->id;
		}
		
		static::update_all(
				static::$__treeLft . ' = ' . static::$__treeLft . " $symbol $moveSpan, " .
				static::$__treeRght . ' = ' . static::$__treeRght . " $symbol $moveSpan",
				[
					'id IN (?)',
					$childrenIds
				]);
		
		return true;
	}
	
	public static function tree($separator = '-', $options = [], $lft = null, $rght = null) {
		if ($lft) static::$__treeLft	= $lft;
		if ($rght)static::$__treeRght = $rght;
		
		static::$__treeSeparator	= $separator;
		
		if (isset($options['title'])) {
			$title = $options['title'];
			unset($options['title']);
		} else {
			$title = 'title';
		}
		
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
			$item->{$title}= static::separatorLevel($level) . $item->{$title};
			 
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
						static::$__treeLft . ' > ? AND ' . static::$__treeRght . ' < ?',
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