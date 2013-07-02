(function($){
	
	$.inputChange = {
		defaults: {
			timeout: false,
			keydown: function(event) {},
			callback: function(event, jqObj) {}
		}
	};
	
	$.fn.inputChange = function(params) {
		var options = $.extend($.inputChange.defaults, params || {});
		
		return this.each(function() {
			if (!$(this).attr('type') || $(this).attr('type') != 'text') return this;
			
			var $this	= this
			var lastText= null;
			var timer 	= null;
			var callback= options.callback;
			var timeout	= options.timeout;
			var keydown	= options.keydown;
			
			
			$($this).keydown(function(event) {
				keydown(event, $($this));
				
				var currentText = $($this).val();
				if (lastText != currentText) {
					if (timeout) {
						timer = setTimeout(function() {
							callback(event, $($this));
						}, timeout);
					} else {
						callback(event, $($this));
					}
				}
				
				lastText = currentText;
			});
		});
	};
})(jQuery);