/**
 * -- jForm jQuery Form plugin --
 * This plugin is meant to automize the creation form validation, tracking,
 * and defaults.
 * 
 * @author Zachary Quintana
 */
(function() {
	
	// Parent Class for Forms and containing elements
	$.Speedy.Form	= $.Speedy.Object.Extend({
		defaults: {
			trackAsPage : false,
			debug		: false,
			addFormId	: false,
			validate	: false
		},
		
		construct: function(selector, settings) {
			this.form	= $(selector); 	// jQuery object containing Form
			this.settings	= $.extend(this.defaults, settings || {});
			this.formElements	= new Array();
			
			// build current uri
			var href = document.location.href;
			href = href.split('.');
			href = href.pop();
			href = href.substring(href.indexOf('/',0), href.length);
			href = (href.length == 1) ? href + "index" : href;
			if (href.charAt(href.length - 1) == "/") href = href.substring(0,href.lastIndexOf('/'));
			this.uri = href;
			
			// clean up actions after initialization
			var $this = this;
			if (this.settings.trackAsPage) this.initTracking();
			this.form.bind('submit', function() { return $this.onSubmit(); });
			
			this.initDefaults();
		},
		
		onSubmit: function() {
			if (this.settings.validate) {
				var isValid	= this.validate();
				
				if (!isValid) return false;
			}
			
			return (this.isDebug()) ? false : true;
		},
		
		isDebug: function() {
			return (this.settings.debug) ? true : false;
		},
		
		initDefaults: function () {
			var $this	= this;
			
			$(":input", this.form).each(function() {
				var type	= $(this).attr('type').toLowerCase(),
					options	= $.extend($this.settings.inputs || {},{ 
						selector: this 
					});
				
				if (type == 'text' || type == 'textarea') {
					$this.formElements.push($.Speedy.Form.Inputs.Text.Create(options));
				} else if (type == 'select') {
					$this.formElements.push($.Speedy.Form.Inputs.Select.Create(options));
				}
			});
			
		
		},
		
		initTracking : function() {
			var $this 	= this;
			
			$(":input", this.jObj).each(function() {
				var page	= ($this.settings.addFormId) ? 
						'/vpv' + $this.uri + '/form:' + $this.jObj.attr('id') + '/' + $(this).attr('name') : '/vpv' + $this.uri + '/' + $(this).attr('name');
				var type	= $(this).attr('type').toLowerCase();
				
				if (type == 'text' 
					|| type == 'checkbox' 
					|| type == 'textarea' 
					|| type == 'radio'
					|| type == 'select') {
					$(this).focusin(function() {
						_gaq.push([ '_trackPageview', page]);
						if ($this.isDebug()) console.log(page);
					});
				}
			});
		},
		
		validate: function() {
			var valid = true;
			$('span.jform-msg').remove();
			//this.resetValidation();
			
			for (var i in this.formElements) {
				if (!this.formElements[i].doValidate()) valid = false;
			}
			
			return valid;
		},
		
		resetValidation: function() {
			for (var i in this.formElements) {
				this.formElements[i].resetValidation();
			}
			
			return this;
		}
	})
	
	
	// Namespace for input elements
	if (!$.Speedy.Form.Inputs) $.Speedy.Form.Inputs	= {};
	
	
	// Base class for all input elements
	// Do not use directly
	$.Speedy.Form.Inputs.Base	= $.Speedy.Object.Extend({
		defaults: {},
		
		construct: function(options) {
			//this.element		= $(selector);				// jQuery object of element
			this.options	= $.extend(this.defaults, options || {});
			
			if (!this.options.selector) {
				throw "Form::Inputs instance require selector option";
			}
			this.setElement(this.options.selector);	// jQuery object of element
			
			this.defaultValue	= this.element.val(); 	
			this.classes		= (this.element.attr('class')) ? this.element.attr('class').split(' ') : '';
			this.valid	= null;

			var $this = this;
			this.element.focusin(function(e) {
				$this.onElementFocusIn();
			});
			
			this.element.focusout(function(e) {
				$this.onElementFocusOut();
			});
			
			return this;
		},
		
		onElementFocusIn: function() {
			if (this.element.val() == this.defaultValue) {
				this.element.val('');
			}
			
			if (!this.isValid()) {
				this.doValidate(false);
			}
		},
		
		onElementFocusOut: function() {
			if (this.element.val().length < 1) {
				this.element.val(this.defaultValue);
			}
			
			if (this.options.onBlurValidate) {
				this.doValidate();
			}
		},
		
		isValid: function() {
			return (this.valid == null || this.valid) ? true : false;  
		},
		
		isVerbose: function() {
			return (this.options.verbose) ? true : false;
		},
		
		
		doValidate: function(verbose) {
			verbose	= verbose || true;
			var valid = true;
			
			if (!this.classes || this.classes.length < 1) return false;
			this.resetValidation();
			
			for (var i in this.classes) {
				switch (this.classes[i]) {
					case "not-empty":
						if (this.isEmpty()) {
							if (verbose) this.outputFail('This field is required');
							valid = false;
						}
						break;
				}
				
				if (!valid) {
					break;
				}
			}
			
			return valid;
		},
		
		isEmpty: function() {
			return (this.element.val().length > 0) ? false : true;
		},
		
		outputFail: function(msg) {
			if (this.isVerbose()) {
				this.getMsgEl()
						.addClass('validation-failed')
						.text(msg)
						.show();
			}
			
			this.element.addClass('vector-form-input-required');
		},
		
		resetValidation: function() {
			this.getMsgEl().removeClass('validation-failed').hide();
			this.element.removeClass('vector-form-input-required');
			return this;
		},
		
		setElement: function(el) {
			this.element	= $(el);
			return this;
		},
		
		getElement: function() {
			return this.element;
		},
		
		getAppendEl: function() {
			if (!this.appendEl) {
				if (this.options.appendTo) {
					this.appendEl	= this.element.parent(this.options.appendTo);
				} else {
					this.appendEl	= this.element;
				}
			}
			
			return this.appendEl;
		},
		
		getMsgEl: function() {
			if (!this.msgEl) {
				var el	= document.createElement('span');
				el.setAttribute('class', 'vector-form-input-msg');
			
				this.getAppendEl().after(el);
				this.msgEl	= $(el);
			}
			
			return this.msgEl;
		}
		
	});
	
	
	// Class for Text elements
	// Shell Class to add input specific methods
	$.Speedy.Form.Inputs.Text	= $.Speedy.Form.Inputs.Base.Extend({
		defaults: {
			onBlurValidate: true
		},
		
		construct: function(options) {
			this._super.construct.call(this, options);
			
			return this;
		}
	});
	
	// Class for Select elements
	$.Speedy.Form.Inputs.Select	= $.Speedy.Form.Inputs.Base.Extend({
		onElementFocusIn: function() {		
			if (!this.isValid()) {
				this.doValidate(false);
			}
		},
		
		onElementFocusOut: function() {
			if (this.options.onBlurValidate) {
				this.doValidate();
			}
		},
		
		doValidate: function(verbose) {
			verbose	= verbose || true;
			var valid = true;
			
			if (!this.classes || this.classes.length < 1) return false;
			this.resetValidation();
			
			for (var i in this.classes) {
				switch (this.classes[i]) {
					case "not-empty":
						if (this.isEmpty()) {
							if (verbose) this.outputFail('This field is required');
							valid = false;
						}
						break;
				}
				
				if (!valid) {
					break;
				}
			}
			
			return valid;
		},
		
		selected: function() {
			return $('option:selected', this.element);
		},
		
		isEmpty: function() {
			return (this.selected().val().length > 0) ? false : true;
		}
	});

})(jQuery);