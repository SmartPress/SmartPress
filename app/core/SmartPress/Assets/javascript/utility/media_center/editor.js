(function($) {
	if (window.MediaCenter == null)
		window.MediaCenter = {};
	
	window.MediaCenter.Editor = function(dialog) {
		var me = this;
		
		this.dialog = dialog;
		this.getScaleWidth().inputChange({
			keydown	: function(evt, el) {
				me.onScaleInputKeydown.call(me, evt, el, me.scaleWidthSelector);
			},
			timeout	: this.inputChangeTimeout,
			callback: function(evt, el) {
				me.onScaleWidthInputChange.call(me, evt, el);
			}
		});
		
		this.getScaleHeight().inputChange({
			keydown	: function(evt, el) {
				me.onScaleInputKeydown.call(me, evt, el, me.scaleHeightSelector);
			},
			timeout	: this.inputChangeTimeout,
			callback: function(evt, el) {
				me.onScaleHeightInputChange.call(me, evt, el);
			}
		});
		
		this.getDialog().getEl().on('dialogresizestop', function(evt, ui) {
			return me.onDialogResizeStop();
		});
		
		return this;
	};

	window.MediaCenter.Editor.prototype = {
		active: false,
		inputChangeTimeout	: 1000,
		scaleLockSelector	: '#media_editor_scale_lock',
		scaleHeightSelector	: '#media_editor_scale_height',
		scaleWidthSelector	: '#media_editor_scale_width',
		cropWidthSelector	: '#media_editor_crop_width',
		cropHeightSelector	: '#media_editor_crop_height',
		cropStartXSelector	: '#media_editor_crop_startx',
		cropStartYSelector	: '#media_editor_crop_starty',
		originalWidthSelector	: '#media_editor_orig_width',
		originalHeightSelector	: '#media_editor_orig_height',
		image: '#media_editor_image',
		statusSelector: '#media_editor_status',
		
		onDialogResizeStop: function() {
			var dialog = this.getDialog();
			
			if (dialog.activeTab() != dialog.GALLERY_TAB)
				return;
			
			this.initSelector();
		},
		
		getStatus: function() {
			return $(this.statusSelector);
		},
		
		getCropStartY: function() {
			return $(this.cropStartYSelector);
		},
		
		getCropStartX: function() {
			return $(this.cropStartXSelector);
		},
		
		getCropHeight: function() {
			return $(this.cropHeightSelector);
		},
		
		getCropHeightVal: function() {
			return (this.heightRatio) ? this.heightRatio * parseInteger(this.getCropHeight().val()) : 
					this.getCropHeight().val(); 
		},
		
		getCropWidth: function() {
			return $(this.cropWidthSelector);
		},
		
		getCropWidthVal: function() {
			return (this.widthRatio) ? this.widthRatio * parseInteger(this.getCropWidth().val()) : 
					this.getCropWidth().val(); 
		},
		
		getImage: function() {
			return $(this.image);
		},
		
		getScaleLock: function() {
			return $(this.scaleLockSelector);
		},
		
		getScaleHeight: function() {
			return $(this.scaleHeightSelector);
		},
		
		getScaleWidth: function() {
			return $(this.scaleWidthSelector);
		},
		
		setStatus: function(status) {
			this.getDialog().getEl().dialog('option', 'title', status);
			this.getStatus().text(status);
			return this;
		},
			
		onScaleInputKeydown: function(evt, el, target) {
			if (evt.keyCode == 38) {
				el.val(parseInt(el.val()) + 1);
				
				if (this.scaleLock().is(':checked')) {
					//alert($('.media-editor-scale-height', $this.jqObj).val());
					$(target).val(parseInt($(target).val()) + 1);
				}
			} else if (evt.keyCode == 40) {
				el.val(parseInt(el.val()) - 1);
				if (this.scaleLock().is(':checked')) {
					$(target).val(parseInt($(target).val()) - 1);
				}
			}
		},
			
		onScaleWidthInputChange: function(evt, el) {
			var image = this.getImage();
			image.attr('width', el.val());
				
			if (this.getScaleLock().is(':checked')) {
				// Change aspect ratio
				var height = parseInt(parseInt(el.val())/this.aspectRatio);
				image.attr('height',height);
				this.getScaleHeight().val(height);
			}
				
			this.initSelector();
		},
			
		onScaleHeightInputChange: function(evt, el) {
			var image = this.imageEl();
			image.attr('height', el.val());
				
			if (this.getScaleLock().is(':checked')) {
				var width = parseInt(parseInt(el.val()) * this.aspectRatio);
				image.attr('width',width);
				this.getScaleWidth().val(width);
			}
				
			this.initSelector();
		},
		
		setEditImage: function(src) {
			var me = this;
			this.getImage().attr('src', src);
			this.getImage().load(function() {
				var width	= this.naturalWidth,
					height	= this.naturalHeight,
					img	= $(this);
				
				//$(this).attr('width', width);
				//$(this).attr('height', height);
				me.aspectRatio = parseFloat(width/height);
				me.initSelector();
				
				if (img.width() != width && 
						img.height() != height) {
					me.widthRatio	= parseFloat(img.width()/width);
					me.heightRatio	= parseFloat(img.height()/height);
				} 
			});
			
			return this;
		},
		
		initSelector: function() {
			if (this.selector) 
				this.removeSelector();
			
			var me = this;
			this.setCropWidth('0')
				.setCropHeight('0')
				.setCropStartX('0')
				.setCropStartY('0');
			
			this.selector	= this.getImage().imgAreaSelect({
				handles	: true,
				instance: true,
				onSelectChange: function(img, selection) {
					me.setCropWidth(selection.width)
						.setCropHeight(selection.height)
						.setCropStartX(selection.x1)
						.setCropStartY(selection.y1);
				}
			});
		},
		
		setCropStartY: function(val) {
			if (this.heightRatio) {
				val = val/this.heightRatio;
			}
			
			this.getCropStartY().val(parseInt(val));
			return this;
		},
		
		setCropStartX: function(val) {
			if (this.widthRatio) {
				val = val/this.widthRatio;
			}
			
			this.getCropStartX().val(parseInt(val));
			return this;
		},
		
		removeSelector: function() {
			this.selector.remove();
			this.getImage().removeData('imgAreaSelect');
			this.selector = null;
			return this;
		},
		
		setCropHeight: function(val) {
			if (this.heightRatio) {
				val = val/this.heightRatio;
			}
			
			this.getCropHeight().val(parseInt(val));
			return this;
		},
		
		setCropWidth: function(val) {
			if (this.widthRatio) {
				val = val/this.widthRatio;
			}
			
			this.getCropWidth().val(parseInt(val));
			return this;
		},
		
		getRequestUrl: function(id) {
			var requestUrl	= this.getDialog().options.resizeUri;
			
			requestUrl = requestUrl.replace(/#\{\id\}/ig, this.id);
			return requestUrl;
		},
		
		getDialog: function() {
			return this.dialog;
		},
		
		save: function() {
			console.log(this.id);
			if (this.currentRequest)
				this.currentRequest.abort();
			
			var requestUrl = this.getRequestUrl(),
				me = this;
			
			this.setStatus('');
			this.currentRequest = $.ajax({
				url	: requestUrl,
				dataType: 'json',
				data: {
					crop: {
					       'width'	: me.getCropWidth().val(),
					       'height'	: me.getCropHeight().val(),
					       'start_x': me.getCropStartX().val(),
					       'start_y': me.getCropStartY().val() 
					},
					scale: {
					       'width'	: me.getScaleWidth().val(),
					       'height'	: me.getScaleHeight().val()
					}
				},
				type: 'POST',
				success	: function(data, textStatus, jqXHR) {
					console.log(data);
					if (data.success) {
						me.onSaved();
					} else {
						me.setStatus('Encountered an error while trying to save.');
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log([textStatus, jqXHR, errorThrown]);
				},
				complete: function(jqXHR, textStatus) {
					me.currentRequest = null;
				}
			});
		},
		
		onSaved: function() {
			this.setStatus('Saved!')
				.removeSelector();
			
			this.getDialog().activateTab(this.getDialog().GALLERY_TAB);
			this.getDialog().getGallery().load();
		},
		
		edit: function(img) {
			this.setEditImage(img.data('src'));
			this.getDialog().activateTab(this.getDialog().EDITOR_TAB);
			
			this.setOriginalWidth(img.data('width'))
					.setOriginalHeight(img.data('height'))
					.setScaleWidth(img.data('width'))
					.setScaleHeight(img.data('height'))
					.setId(img.data('id'));
		},
		
		onShow: function() {
			var me = this,
				dialog = this.getDialog();
			
			dialog.addButtons([{
				text: "Save",
				click: function() {
					me.save();
				}
			},{
				text: "Cancel",
				click: function() {
					dialog.close();
				}
			}]);
		},
		
		setId: function(id) {
			this.id = id;
			return this;
		},
		
		setOriginalWidth: function(width) {
			$(this.originalWidthSelector).text(width);
			return this;
		},
		
		setOriginalHeight: function(height) {
			$(this.originalHeightSelector).text(height);
			return this;
		},
		
		setScaleWidth: function(width) {
			this.getScaleWidth().val(width);
			return this;
		},
		
		setScaleHeight: function(height) {
			this.getScaleHeight().val(height);
			return this;
		}
	};
})(jQuery);