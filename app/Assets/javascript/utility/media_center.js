(function($){
	var MediaEditor = function() {
		var me = this;
		this.scaleWidth().inputChange({
			keydown	: function(evt, el) {
				me.onScaleInputKeydown.call(me, evt, el, me.scaleWidthSelector);
			},
			timeout	: this.inputChangeTimeout,
			callback: function(evt, el) {
				me.onScaleWidthInputChange.call(me, evt, el);
			}
		});
		
		this.scaleHeight().inputChange({
			keydown	: function(evt, el) {
				me.onScaleInputKeydown.call(me, evt, el, me.scaleHeightSelector);
			},
			timeout	: this.inputChangeTimeout,
			callback: function(evt, el) {
				me.onScaleHeightInputChange.call(me, evt, el);
			}
		});
		
		return this;
	};

	MediaEditor.prototype = {
		inputChangeTimeout	: 1000,
		scaleLockSelector	: '#media_editor_scale_lock',
		scaleHeightSelector	: '#media_editor_scale_height',
		scaleWidthSelector	: '#media_editor_scale_width',
		cropWidthSelector	: '#media_editor_crop_width',
		cropHeightSelector	: '#media_editor_crop_height',
		cropStartXSelector	: '#media_editor_crop_startx',
		cropStartYSelector	: '#media_editor_crop_starty',
		image: '#media_editor_image',
		
		cropStartY: function() {
			return $(this.cropStartYSelector);
		},
		
		cropStartX: function() {
			return $(this.cropStartXSelector);
		},
		
		cropHeight: function() {
			return $(this.cropHeightSelector);
		},
		
		cropWidth: function() {
			return $(this.cropWidthSelector);
		},
		
		imageEl: function() {
			return $(this.image);
		},
		
		scaleLock: function() {
			return $(this.scaleLockSelector);
		},
		
		scaleHeight: function() {
			return $(this.scaleHeightSelector);
		},
		
		scaleWidth: function() {
			return $(this.scaleWidthSelector);
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
			var image = this.imageEl();
			image.attr('width', el.val());
				
			if (this.scaleLock().is(':checked')) {
				// Change aspect ratio
				var height = parseInt(parseInt(el.val())/this.aspectRatio);
				image.attr('height',height);
				this.scaleHeight().val(height);
			}
				
			// TODO
			//$this.resetImgSelector();
			//$this.initImgSelector();
		},
			
		onScaleHeightInputChange: function(evt, el) {
			var image = this.imageEl();
			image.attr('height', el.val());
				
			if (this.scaleLock().is(':checked')) {
				var width = parseInt(parseInt(el.val()) * this.aspectRatio);
				image.attr('width',width);
				this.scaleWidth().val(width);
			}
				
			// TODO
			//$this.resetImgSelector();
			//$this.initImgSelector();
		},
		
		setEditImage: function(src) {
			var me = this;
			this.imageEl().attr('src', src);
			this.imageEl().load(function() {
				var width	= this.naturalWidth,
					height	= this.naturalHeight;
				
				me.aspectRatio = parseFloat(width/height);
				me.initSelector();
			});
			
			return this;
		},
		
		initSelector: function() {
			if (this.selector) {
				this.selector.remote();
				this.imageEl().removeData('imgAreaSelect');
				this.selector = null;
			}
			
			var me = this;
			this.cropWidth().val('0');
			this.cropHeight().val('0');
			this.cropStartX().val('0');
			this.cropStartY().val('0');
			
			this.selector	= this.imageEl().imgAreaSelect({
				handles	: true,
				instance: true,
				onSelectChange: function(img, selection) {
					me.cropWidth().val(selection.width);
					me.cropHeight().val(selection.height);
					me.cropStartX().val(selection.x1);
					me.cropStartY().val(selection.y1);
				}
			});
		}
	};
	
	
	var MediaDialog = function(referrer, options) {
		this.options	= $.extend(this.defaults, options || {});
		this.referrer	= $(referrer);
	};
	
	MediaDialog.prototype = {
		UPLOAD_TAB	: 0,
		IMAGES_TAB	: 1,
		EDITOR_TAB	: 2,
			
		buildContainer	: function() {
			var container	= document.createElement('div');
			container.setAttribute('class', 'media-holder');
				
			$('body').append(container);
			
			var dialog = $(container),
				tpl	= this.format.join("\n"),
				me	= this;
			
			dialog.hide();
			tpl	= tpl.replace(/{identifier}/ig, this.options.identifier);
			tpl	= tpl.replace(/{uploadUrl}/ig, this.options.uploadUrl);
			dialog.append(tpl);
			dialog.dialog({
				autoOpen: false,
				width	: this.options.width,
				height	: this.options.height
			});
			
			this.dialogEl = dialog;
			this.editor = new MediaEditor();
			this.tabs().tabs({
				disabled: me.options.disabledTabs,
				show	: function(event, ui) {
					me.onTabShow.call(me, event, ui);
				},
				close	: function(event, ui) {
					me.cleanup.call(me);
				}
			});
			
			return dialog;
		},
		
		onTabShow	: function(evt, ui) {
			switch (ui.index) {
				case this.EDITOR_TAB:
					this.onEditorTabShow.call(this, evt, ui);
					break;
					
				case this.IMAGES_TAB:
					this.onImageTabShow.call(this, evt, ui);
					break;
			}
		},
		
		onEditorTabShow: function(evt, ui) {
			
		},

		setEditImage: function(src) {
			this.editor.setEditImage(src);
			
			return this;
		},
		
		onImageTabShow: function(evt, ui) {
			if (this.images.length > 0) 
				return true;
			
			var me	= this; 
			$.getJSON('/admin/uploads.json', function(data) {
				var images	= me.images = data.files,
					//el	= dialog.getContentElement('add-image-tab', 'image-select').getElement(),
					tpl	= me.imageTpl.join("\n");
				
				$("#images-selection .image-row").remove();
				for (var i = 0; i < images.length; i++) {
					var image = images[i], newEntry;
					
					newEntry	= tpl.replace(/\{\$src\}/ig, image.filename);
					newEntry	= newEntry.replace(/\{\$width\}/ig, image.width);
					newEntry	= newEntry.replace(/\{\$height\}/ig, image.height);
					newEntry	= newEntry.replace(/\{\$id\}/ig, image.id);
					$("#images-selection").append(newEntry);
				}
				
				$('.media-library-image-link').click(function(evt) {
					me.onMediaImageLinkClick.call(me, $(this), evt);
				});
				
				$('#media_editor_link').click(function(evt) {
					me.onEditorLinkClick.call(me, $(this), evt);
				});
			});
		},
		
		/**
		 * Choose thumb link action
		 */
		onMediaImageLinkClick: function(el, evt) {
			var id = el.data('id'),
				cont = $('tr.image-thumb[data-id="' + id + '"]'),
				tpl	= this.definition.insertTpl.join("\n"),
				me	= this;
			
			$('tr.insert_image_dialog').remove();
			$('tr.image-thumb').after(tpl);
			$('tr.insert_image_dialog').slideDown();
			$('#insert_image_link_url').val(el.data('src'));
			
			$('#insert_image_into_post').on('click', function(evt) {
				me.definition.onInsertButtonClick.call(me, $(this), evt);
			});
		},
		
		/**
		 * Send to editor link action
		 */
		onEditorLinkClick: function(el, evt) {
			//this.showPage('edit-image-tab');
			//this.selectPage('edit-image-tab');
			this.setEditImage(el.data('src'));
			this.tabs().tabs('enable', this.EDITOR_TAB);
			this.tabs().tabs('select', this.EDITOR_TAB);
			
			$('#media_editor_orig_width').text(el.data('width'));
			$('#media_editor_orig_height').text(el.data('height'));
			
			this.editor.scaleWidth().val(el.data('width'));
			this.editor.scaleHeight().val(el.data('height'));
		},
		
		container: function() {
			return $(".media-holder");
		},
		
		tabs: function() {
			return $(this.options.tabs);
		},
		
		dialog: function() {
			return this.dialogEl;
		},
		
		open: function() {
			this.buildContainer();
			this.dialog().dialog("open");
			return this;
		},
		
		close: function() {
			this.dialog().dialog('close');
			this.cleanup();
		},
		
		cleanup: function() {
			this.container().remove();
		},
		
		images	: [],
		
		defaults	: {
			tabs		: '.media-tabs',			// Selector for tabs
			galleryLink	: '.media-gallery',			// Selector for galley link
			results		: '.media-results',			// Selector for media results panel
			editorLink	: '.media-edit',			// Selector for editor link button
			editorImage	: '.media-editor-image',	// Selector for media editor image
			prefix		: null,						// Potential Prefix for image update
			identifier	: '__media',				// Not sure why I added this
			libraryUri	: '/admin/uploads',	// Libray Uri
			resizeUri	: '/admin/uploads/image_resize',	// Resize Uri
			uploadUri	: '/admin/uploads',		// Upload Uri
			imageBase	: '/uploads/',
			disabledTabs: [2],
			bindLinksOnInit	: false,
			onUploadSuccess	: function(data) {},
			width	: 400,
			height	: 300
		},
			
		format: [ 
					'<div class="media-dialog" title="Media Editor">',
					'<div class="media-tabs">',
						'<ul>',
							'<li><a href="#{identifier}-upload-form">Upload</a></li>',
							'<li><a href="#{identifier}-media-library" class="media-gallery">Media Library</a></li>',
							'<li><a href="#{identifier}-media-editor" class="media-editor-link">Media Editor</a></li>',
						'</ul>',
						'<div id="{identifier}-upload-form">',
							'<form accept-charset="utf-8" action="{uploadUrl}" method="post" enctype="multipart/form-data" class="UploadAdminAddForm">',
								'<input type="hidden" value="POST" name="_method">',
								'<div class="input file"><label for="UploadFile">File</label><input type="file" class="UploadFile" name="upload"></div>',
								'<div class="submit">',
									'<input type="submit" value="Submit">',
									'<span class="media-upload-form-success"></span>',
								'</div>',
							'</form>',
						'</div>',
						'<div id="{identifier}-media-library" class="media-library">',
							'<div class="media-results">',
								'<table id="images-selection" width="100%" style="margin:0;width:100%;">',
									'<tr>',
										'<th>Thumb</th>',
										'<th>Details</th>',
										'<th>Actions</th>',
									'</tr>',
								'</table>',
							'</div>',
							'<div class="media-results-pagination" class="ui-widget-header ui-corner-all" style="margin-top: 20px;"></div>',
						'</div>',
						'<div id="{identifier}-media-editor" class="media-editor">',
							'<div class="media-editor-image-cont">',
								'<img src="" id="media_editor_image" />',
							'</div>',
							'<div class="media-editor-details">',
								'<fieldset>',
									'<legend>Details</legend>',
									'<input type="hidden" name="data[Image][id]" />',
									'<table width="100%">',
										'<tr>',
											'<th>Original</th>',
											'<th>Scale <span class="checkbox"><input type="checkbox" name="lock" id="media_editor_scale_lock" checked="checked" /> <label>Lock</label></span></th>',
											'<th>Crop</th>',
										'</tr>',
										'<tr>',
											'<td>',
												'<label>Width:</label> <span id="media_editor_orig_width"></span><br />',
												'<label>Heigh:</label> <span id="media_editor_orig_height"></span>',
											'</td>',
											'<td>',
												'<div><label>Width:</label> <input type="text" name="scale[width]" id="media_editor_scale_width" style="width: 50px;" /></div>',
												'<div><label>Height:</label> <input type="text" name="scale[height]" id="media_editor_scale_height" style="width: 50px;" /></div>',
											'</td>',
											'<td>',
												'<label>Width:</label> <input type="text" id="media_editor_crop_width" name="crop[width]" value="0" style="width: 50px;" /><br />',
												'<label>Height:</label> <input type="text" id="media_editor_crop_height" name="crop[height]" value="0" style="width: 50px;" />',
													'<input type="hidden" name="crop[start_x]" id="media_editor_crop_startx" value="" />',
													'<input type="hidden" name="crop[start_y]" id="media_editor_crop_starty" value="" />',
											'</td>',
										'</tr>',
									'</table>',
									'<div class="submit">',
										'<input type="button" value="Save" class="media-editor-save-edited" />',
										'<span class="media-editor-save-edited-status"></span>',
									'</div>',
								'</fieldset>',
							'</div>',
						'</div>',
					'</div>',
				'</div>'
			],
			
		imageTpl: [
		           '<tr class="image-row" data-id="{$id}">',
		           		'<td>',
			           		'<a href="javascript:void(0)" class="media-library-image-link" data-id="{$id}" data-src="/uploads/{$src}"><img src="/uploads/{$src}" width="75" height="50" border="0" class="media-library-image" style="width:75px;height:50px;" /></a><br/>',
			           	'</td>',
			           	'<td>',
			           		'Width: {$width}<br />',
			           		'Height: {$height}<br />',
			           	'</td>',
			           	'<td>',
			           		'<a href="javascript:void(0)" id="media_editor_link" data-src="/uploads/{$src}" data-id="{$id}" data-width="{$width}" data-height="{$height}">Send to Editor</a><br />',
			           		'<a href="javascript:void(0)" id="media_editor_delete_link" data-id="{$id}">Delete</a><br />',
			           	'</td>',
					'</tr>'
		],
	};
	
	$._mediaCenter = {};
	
	$.mediaCenter = function(referrer, options) {
		$._mediaCenter.dialog = new MediaDialog(referrer, options);
		return $._mediaCenter.dialog;
	};
	
	$.fn.mediaCenter = function(options) {
		return $(this).each(function(index) {
			var me = this;
			
			$(this).click(function() {
				var dialog = $.mediaCenter(me, options);
				dialog.open();
			});
		});
	};
	
	
	
})(jQuery);
