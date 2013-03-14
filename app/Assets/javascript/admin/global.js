(function($) {
	
	$(document).ready(function() {
		$('.collapse').collapse();
		// $('.collapse').on('shown', function() { console.log($('.collapse')); });
		
		//$('[data-target="modal"]').modal();
		$('[data-role="tabs"]').tab('show');
	})
	
	
}) (jQuery);