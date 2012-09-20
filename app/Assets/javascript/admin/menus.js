(function($) {
	
	$(document).ready(function() {
		$('ul.sortable li').draggable({
			done: function(offset) {
				console.log("Done " + offset);
			},
			moved: function(offset) {
				console.log('Moved ' + offset);
			}
		});
	});
	
})(jQuery);