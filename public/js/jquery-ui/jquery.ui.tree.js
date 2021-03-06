/*
 * jQuery UI Tree @VERSION
 *
 *
 * http://docs.jquery.com/UI/Accordion
 *
 * Depends:
 *	jquery.ui.core.js
 *	jquery.ui.widget.js
 */

(function($, undefined) {
	
	$.widget( 'ui.tree', {
		options: {
			active: 0,
			effect: "slide",
			effectOptions: {},
			ajax: false,
			altClass: 'ui-tree-alt-row',
			event: 'click',
			icons: {
				activeHeader: "ui-icon-triangle-1-s",
				header: "ui-icon-triangle-1-e"
			},
			requestUri: null,
			widgetClasses: 'ui-widget ui-helper-reset',
			parent: '0'
		},
		
		_create: function() {
			this.currentRequest	= null;
			
			
			var self	= this,
				options	=	self.options;
			
			self.element.addClass('ui-tree');
			if (typeof(self.options.widgetClasses.length) == 'string') 
				self.element.addClass(self.options.widgetClasses);
			
			
			
			if (self.element.tagName == 'UL') 
				self._createNestedList();
			else if (self.element[0].tagName == 'DIV' 
					|| self.element[0].tagName == 'TR')
				self._createFlat();
		},
		
		_createFlat: function() {
			var self	= this;
			self.element
				.each(function() {
					var el	= $(this),
						id	= el.attr('id'),
						parent = el.data('parent');
					
					if (parent != self.options.parent)
						el.hide();
					
					if ($('.ui-tree[parent="' + id + '"]')) {
						el.bind(self.options.event, function() {
							self._eventFlatHandler(el);
						});
					}
				});
		},
		
		_createNestedList: function() {
			var self	= this;
			self.element.find('ul')
				.hide();
			
			self.element.find('li').each(function() {
				var el	= $(this);
				
				if (el.find('ul')) {
					el
						.css('ui-collapsible-header ui-helper-reset ui-state-default')
						.attr('role', 'tab');
					
					el.bind(self.options.event, function() {
						self._eventNestedHandler(el);
					});
				}
			});	
		},
		
		_eventFlatHandler: function(el) {
			el = $('.ui-tree[data-parent="' + el.attr('id') + '"]');
			var test 	= el.is(':visible');
			
			if (test) {
				this._collapseAll(el);
			} else {
				this._toggleEffect(el);
			}
		},
		
		_collapseAll: function(el) {
			var self	= this;
			el.each(function() {
				var currentEl = $('.ui-tree[parent="' + $(this).attr('id') + '"]');
				if (el.length > 0) self._collapseAll(currentEl);
				
				if ($(this).is(':visible')) {
					if (self.options.effect)
						currentEl.effect(self.options.effect, $.extend(self.options.effectOptions, { mode: 'hide' }));
					else $(this).hide();
				}
			});
		},
		
		_eventNestedHandler: function(el) {
			this._toggleEffect(el.find('ul'));
		},
		
		_toggleEffect: function(el) {
			if (el.is(':hidden')) 
				if (this.options.effect)
					el.effect(this.options.effect, $.extend(this.effectOptions, { mode: 'show' }));
				else el.show();
			else 
				if (this.options.effect)
					el.effect(this.options.effect, $.extend(this.effectOptions, { mode: 'hide' }));
				else el.hide();
		}
		
	});
	
})(jQuery);