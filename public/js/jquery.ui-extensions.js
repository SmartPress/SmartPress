(function($) {
	function VectorEffect(jqObj, options) {
		this.selectedEffect	= options.effect;
		this.settings	= options;
		this.jqObj	= jqObj;
		
		this.initialize();
	}
	
	VectorEffect.prototype = {
		initialize: function() {
			this.effect[this.selectedEffect](this);
		},
	
		effect: {
			highlight: function(parent) {
				var backgroundColor	= parent.jqObj.css('background-color');
				
				parent.jqObj.each(function(index) {
					$(this).hover(
							function(event){ 
								$(this).children().css('background-color', '');
								$(this).css('background-color', parent.settings.highlightColor);
							}, 
							function(event) {
								backgroundColor	= (backgroundColor != null && backgroundColor.length > 0) ? 
									backgroundColor : '';
								$(this).css('background-color', backgroundColor);
							}
						);
				});
			}
		}
	}
	
	
	$.vectorEffect = {
		vectorEffects: new Array(),
		
		defaults: {
			effect: 'highlight',
			highlightColor: '#FDFDA4'
		},
		
		init: function(jqObj, params) {
			var settings = $.extend($.vectorEffect.defaults, params || {});
			$.vectorEffect
						.vectorEffects
						.push(new VectorEffect(jqObj, settings));
		}
	}
	
	$.fn.vectorEffect = function(params) {
		return $(this).each(function(index) {
			$.vectorEffect.init($(this), params);
		});
	}
}(jQuery));