(function($) {
	
	if (!$.Speedy) $.Speedy	= {};
	
    $.Speedy.SortMenu	= $.Speedy.Object.Extend({
    	currentStart	: null,
		currentEnd		: null,
		
		calculateDiff	: function() {
			return this.getCurrentEnd() - this.getCurrentStart();
		},
		
		getCurrentStart	: function() {
			return (this.currentStart) ? this.currentStart : 0;
		},
		
		setCurrentStart	: function(start) {
			this.currentStart	= start;
			return this;
		},
		
		getCurrentEnd	: function() {
			return (this.currentEnd) ? this.currentEnd : 0;
		},
		
		setCurrentEnd	: function(end) {
			this.currentEnd	= end;
			return this;
		},
		
		construct: function(selector, params) {
			var me	= this;
			
			if (!selector) {
				console.warn('sortMenu class requires selector');
				return false;
			}
			
			$(selector).sortable({
				start: function(event, ui) {
					var el		= ui.item[0],
						index	= $(el).parent().children().index(el);
						
					me.setCurrentStart(index);
				},
				stop: function(event, ui) {
					var el		= ui.item[0],
						index	= $(el).parent().children().index(el);
				
					me.setCurrentEnd(index);
					if (params.stop && typeof params.stop == 'function') {
						if (me.calculateDiff() == 0) {
							return; 
						}
						
						params.stop.call(me, el);
					}				
				}
			});
			
			$('.menuItems.sortable').disableSelection();
			
			this._super.construct();
			//console.log(this);
		}
    });

    $.fn.sortMenu = function(params) {
    	return this.each(function() {
    	 	$.Speedy.SortMenu.Create(this, params); 
    	});
    };    
})(jQuery);