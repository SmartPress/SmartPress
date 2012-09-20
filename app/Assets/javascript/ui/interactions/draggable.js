(function($) {
	
	$.sUI = {};
	
	$.sUI.draggable	= function(el, options) {
		var me	= this,
			params	= $.extend(this.defaults, options);
		
		this.movedCallback	= params.moved;
		this.doneCallback	= params.done;
		this.el = $(el);
		this.el.on({
			mousedown: function(evt) {
				return me.onMouseDown(evt);
			},
			
			mouseup: function() {
				return me.onMouseUp();
			},
			
			mouseleave: function() {
				return me.onMouseLeave();
			},
			
			mouseenter: function() {
				return me.onMouseEnter();
			},
			
			mousemove: function(evt) {
				return me.onMouseMove(evt);
			},
			
			mouseover: function(evt) {
				return me.onMouseOver(evt);
			}
		});
		
		this.setHold(false);
		this.disableSelection();
	};
	
	$.sUI.draggable.prototype	= {
			
		defaults: {
			moved	: function(offset) {},
			done	: function(offset) {}
		},
			
		up	: 1,
		down: 2,
		offset: 0,
			
		onMouseDown: function(evt) {
			this.currentEvt = evt;
			this.position = {
					x: evt.offsetX,
					y: evt.offsetY
			}
			this.setHold(true);
		},
		
		onMouseUp: function() {
			$(this.placeholder).after(this.el);
			this.setHold(false);
			
			this.doneCallback(this.offset);
		},
		
		onMouseLeave: function() {
			this.setHold(false);
		},
		
		onMouseEnter: function() {
		},
		
		onMouseMove: function(evt) {
			if (!this.isHolding) return;
			this.currentEvt = evt;
			this.setBoxPosition();
			 
			var next = $(this.placeholder).next(),
				prev = $(this.placeholder).prev(),
				//prev= (tPrev) ? tPrev.prev() : null,
				nOffset	= (next) ? next.offset() : null,
				pOffset	= (prev) ? prev.offset() : null,
				//pPOffset= (pPrev)? pPrev.offset(): null,
				cOffset	= this.el.offset();
			
			if (nOffset && (nOffset.top - next.innerHeight()) < cOffset.top) {
				this.movePlaceholder(this.down);
			} else if (pOffset && pOffset.top > cOffset.top) {
				this.movePlaceholder(this.up);
			} /*else if (pPOffset && pPOffset.top > cOffset.top) {
				this.movePlaceholder(this.up);
			}*/
		},
		
		onMouseOver: function(evt) {
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
		
		release: function() {
			this.el.removeClass('draging');
			this.el.attr('style', '');
			this.removePlaceholder();
		},
		
		setHold: function(hold) {
			if (this.isHolding == true && !hold) 
				this.release();
			
			this.isHolding = hold;
			
			if (this.isHolding) {
				this.hold();
			} 
		},
		
		setBoxPosition: function() {
			if (!this.currentEvt) return;
			
			this.el.css({
				'left': this.currentEvt.pageX - this.position.x,
				'top' : this.currentEvt.pageY - this.position.y
			});
		},
		
		hold: function() {
			this.offset = 0;
			this.el.css({
				'width'	: this.el.outerWidth(),
				'height': this.el.outerHeight()
			});
			this.setBoxPosition();
			this.el.addClass('draging');
			
			this.addPlaceholder(this.el, this.down);
		},
		
		removePlaceholder: function() {
			$(this.placeholder).remove();
		},
		
		addPlaceholder: function(after, direction) {
			if (this.placeholder) {
				this.removePlaceholder();
			}
			
			this.placeholder = document.createElement('li');
			this.placeholder.setAttribute('class', 'placeholder');
			
			if (direction == this.down) 
				$(after).after(this.placeholder);
			else if (direction == this.up)
				$(after).before(this.placeholder);
		},
		
		disableSelection: function() {
			this.el
				.parent()
				.attr('unselectable', 'on')
				.css('user-select', 'none')
				.on('selectstart', false);
		}
		
	};
	
	$.fn.draggable = function(options) {
		return this.each(function() {
			return $(this).data('draggable', new $.sUI.draggable(this, options));
		});
	};
	
})(jQuery);