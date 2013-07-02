(function($) {
	
	var SortMenu	= function(selector, params) {
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
	};
	
    SortMenu.prototype	= {
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
		}
    };

    $.fn.sortMenu = function(params) {
    	return this.each(function() {
    	 	return $(this).data('sortMenu', new SortMenu(this, params)); 
    	});
    };    
	
	
	$(document).ready(function() {
		$('ul.sortable').sortMenu({
			stop: function(el) {
				$.ajax({
					url 	: '/admin/menus/' + el.dataset['id'] + '/move.json?&offset=' + this.calculateDiff(),
					dataType: 'json',
					type: 'POST',
					success	: function(data, textStats, jqXHR) {
						console.log(data);
						if (!data || !data.success) {
							//alert('Move not saved!');
						}
					},
					failure	: function() {
						console.log('Error');
						//alert('Move not saved!');
					}
				});
			}
		});
	});
	
})(jQuery);