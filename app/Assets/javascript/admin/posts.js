(function($) {
	
	$.Speedy.Class('DelayedEvent', {
		
		defaults: {
			delay: 500,
			callback: function() {}
		},
		
		construct: function(el, event, options) {
			var params = $.extend(this.defaults, options);
			
			this.el	= $(el);
			this.callback = params.callback;
			this.delay = params.delay;
			
			var me = this;
			this.el.bind(event, function(event) {
				me.call();
			});
		},
		
		call: function() {
			if (this.timer) {
				clearTimeout(this.timer);
			}
			
			var me = this;
			this.timer = setTimeout(function() {
				me.callback(me);
			}, this.delay);
			return;
		}
		
	});
	
	$.fn.don = function(event, params) {
		return this.each(function() {
			$.Speedy.New('DelayedEvent', this, event, params);
		});
	};
	
	$.Speedy.Class('AutoSlug', {
		
		construct: function(el) {
			this.el = el;
			var target = this.el.data('target'), 	// Form field
				preview= this.el.data('preview'),	// Probably not one
				editBtn= this.el.data('edit-btn');
	
			if (!target) {
				throw "Target required data element for SlugInput element";
			}
			
			if (preview && !editBtn) {
				throw "Edit button must be set while using preview feature";
			}
			
			this.target = $("#" + target);
			if (preview) {
				this.preview= $("#" + preview);
				this.editBtn= $("#" + editBtn);
				this.target.parent().hide();
				this.editable = false;
			}
			
			var me = this;
			this.el.don('keyup', {
				callback: function(evt) {
					me.keyup();
				},
				delay: 400
			});
			
			this.editBtn.click(function() {
				me.toggleEdit();
			})
		},
		
		keyup: function() {
			this.target.text(this.slugize());
		},
		
		slugize: function() {
			var slug = this.el.val()
						.toLowerCase()
						.replace(/[^\w ]+/g, '')
						.replace(/ +/g,'-');
			
			if (this.preview) this.preview.text(slug);
			this.target.val(slug);
		},
		
		toggleEdit: function() {
			if (this.editable) {
				this.preview.show();
				this.target.parent().hide();
				this.editBtn.text('Edit');
				this.editable = false;
			} else {
				this.preview.hide();
				this.target.parent().show();
				this.editBtn.text('Finish');
				this.editable = true;
			}
		}
		
	});
	
	$(document).ready(function() {
		$("#post_title").each(function() {
			$.Speedy.New('AutoSlug', $(this));
		})
	});
	
})(jQuery);