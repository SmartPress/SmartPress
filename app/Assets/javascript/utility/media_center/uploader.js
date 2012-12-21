(function($) {
	if (window.MediaCenter == null)
		window.MediaCenter = {};
	
	/**
	 * Class for uploading the media
	 */
	window.MediaCenter.Uploader = function(dialog) {
		var me = this;
		
		this.dialog = dialog;
		/*this.getForm().submit(function() {
			return me.upload(); 
		});*/
		
		return this;
	};
	
	window.MediaCenter.Uploader.prototype = {
		selector: {
			status: '#media-upload-form-success'
		},
		
		getDialog: function() {
			return this.dialog;
		},
		
		getForm: function() {
			var selector = "#" + this.getDialog().getIdentifier() + 
				"-upload-form form";
			return $(selector);
		},
		
		getStatus: function() {
			return $(this.selector.status);
		},
		
		setStatus: function(status) {
			this.getDialog().getEl().dialog('options','title', status);
			this.getStatus().text(status);
		},
		
		uploadUrl: function() {
			return this.getDialog().options.uploadUri;
		},
		
		upload: function() {
			var me = this;
			this.setStatus('');
			
			this.getForm().ajaxSubmit({
				beforeSubmit: function(formData, jqForm, options) {
					options.dataType = 'json';
					options.url	= me.uploadUrl();
				},
				success: function(responseText, statusText, xhr, form) {
					console.log(responseText);
					if (responseText.success) {
						me.setStatus('Success');
					} else {
						me.setStatus('Errors');
						console.log(responseText.errors);
					}
				}
			});
			
			return false;
		},
		
		onShow: function() {
			var me = this;
			
			this.getDialog().addButtons([{
				text: 'Upload',
				click: function() {
					me.upload();
				}
			},{
				text: 'Cancel',
				click: function() {
					me.getDialog().close();
				}
			}]);
		}
	};
})(jQuery);