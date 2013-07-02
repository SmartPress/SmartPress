(function($) {
	
	if (!$.sUI) $.sUI = {};
	
	$.sUI.sortable = function(el, options) {
		var me = this;
		
		this.el	= $(el);
		this.el.children.each(function(el) {
			me.setEventsForChild(el);
			return;
		});
	};
	
	$.sUI.sortable.prototype = {
			
			setEventsForChild:	function(el) {
				var me = this;
				$(el).on({
					mousedown: function() {
						me.onChildMouseDown(this);
					}
				});
			},
			
			onChildMouseDown: function(el) {
				$(el).draggable();
			},
			
			removePlaceholder: function() {
				$(this.placeholder).remove();
			},
			
			addPlaceholder: function(after, direction) {
				if (this.placeholder) {
					this.removePlaceholder();
				}
				
				this.placeholder = this.createPlaceholder('li');
				
				if (direction == this.down) 
					$(after).after(this.placeholder);
				else if (direction == this.up)
					$(after).before(this.placeholder);
			},
			
			movePlaceholder: function(direction) {
				var rmPlaceholder = $(this.placeholder);
				
				if (direction == this.down){
					this.addPlaceholder(rmPlaceholder.next(), this.down);
					this.offset += 1;
				} else if (direction == this.up) {
					this.addPlaceholder(rmPlaceholder.prev(), this.up)
					this.offset -= 1;
				}
					
				rmPlaceholder.remove();
				this.movedCallback(this.offset);
			},

			createPlaceholder: function(el) {
				if (!el)
					el = 'div';

				var dom = document.createElement(el);
				dom.setAttribute('class', 'placeholder');

				return dom;
			} 
			
	};
	
	$.fn.sortable	= function(options) {
		return this.each(function() {
			return $(this).data('sortable', new $.sUI.sortable(this, options));
		});
	}
	
})(jQuery);