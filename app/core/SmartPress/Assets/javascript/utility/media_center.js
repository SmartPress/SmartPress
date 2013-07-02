//= require "media_center/editor"
//= require "media_center/gallery"
//= require "media_center/uploader"

(function($){
	if (window.MediaCenter == null)
		window.MediaCenter = {};
	
	/**
	 * Media Dialog class
	 * builds a dialog and handles dialog logic
	 */
	window.MediaCenter.Dialog = function(owner, options) {
		this.options	= $.extend(this.defaults, options || {});
		this.owner 		= $(owner);
		this.referrer	= (this.options.ckeditor) ? owner : $(this.owner.data('target'));

		if (this.options.ckeditor)
			return this;

		var me = this;
		this.owner.click(function() {
			me.open();
		});
		
		return this;
	};
	
	window.MediaCenter.Dialog.prototype = {
		UPLOAD_TAB	: 0,
		GALLERY_TAB	: 1,
		EDITOR_TAB	: 2,
			
		buildContainer	: function() {
			var container	= document.createElement('div');
			container.setAttribute('class', 'media-holder');
				
			$('body').append(container);
			
			var dialog = $(container),
				tpl	= this.format.join("\n"),
				me	= this;
			
			dialog.hide();
			tpl	= tpl.replace(/#\{identifier\}/ig, this.options.identifier);
			tpl	= tpl.replace(/#\{uploadUrl\}/ig, this.options.uploadUrl);
			dialog.append(tpl);
			dialog.dialog({
				autoOpen: false,
				width	: this.options.width,
				height	: this.options.height
			});
			
			this.dialogEl = dialog;
			
			this.editor = new MediaCenter.Editor(this);
			this.uploader = new MediaCenter.Uploader(this);
			this.gallery= new MediaCenter.Gallery(this);
			
			this.getTabs().tabs({
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
			console.log(ui.index);
			this.clearButtons();
			this.getEl().dialog('option', 'title', '');
			
			switch (ui.index) {
				case this.UPLOAD_TAB:
					this.getUploader().onShow();
					break;
					
				case this.EDITOR_TAB:
					this.getEditor().onShow();
					break;
					
				case this.GALLERY_TAB:
					this.getGallery().onShow();
					break;
			}
		},
		
		activateTab: function(index) {
			this.getTabs().tabs('enable', index);
			this.getTabs().tabs('select', index);
			return this;
		},
		
		activeTab: function() {
			return this.getTabs().tabs('option', 'active');
		},
		
		clearButtons: function() {
			this.getEl().dialog('option', 'buttons', []);
		},
		
		addButtons: function(buttons) {
			this.getEl().dialog("option", "buttons", buttons);
		},

		getOwnerEl 	: function() {
			return this.owner;
		},

		getReferrerEl : function() {
			return this.referrer;
		},
		
		getGallery	 : function() {
			return this.gallery;
		},

		getPreviewEl : function() {
			return $(this.getOwnerEl().data('preview'));
		},
		
		getContainer: function() {
			return $(".media-holder");
		},
		
		getTabs: function() {
			return $(this.options.tabs);
		},
		
		getEl: function() {
			return this.dialogEl;
		},
		
		getEditor: function() {
			return this.editor;
		},
		
		getUploader: function() {
			return this.uploader;
		},
		
		getIdentifier: function() {
			return this.options.identifier;
		},
		
		open: function() {
			this.buildContainer();
			this.getEl().dialog("open");
			return this;
		},
		
		close: function() {
			this.getEl().dialog('close');
			this.cleanup();
		},
		
		cleanup: function() {
			this.getContainer().remove();
		},
		
		defaults	: {
			tabs		: '.media-tabs',			// Selector for tabs
			galleryLink	: '.media-gallery',			// Selector for galley link
			results		: '.media-results',			// Selector for media results panel
			editorLink	: '.media-edit',			// Selector for editor link button
			editorImage	: '.media-editor-image',	// Selector for media editor image
			prefix		: null,						// Potential Prefix for image update
			identifier	: '__media',				// Not sure why I added this
			libraryUri	: '/admin/uploads.json',	// Libray Uri
			resizeUri	: '/admin/images/#{id}/resize.json',	// Resize Uri
			uploadUri	: '/admin/uploads.json',		// Upload Uri
			deleteUri	: '/admin/uploads/#{id}.json',
			imageBase	: '/uploads/',
			disabledTabs: [2],
			bindLinksOnInit	: false,
			width	: 600,
			height	: 300,
			onUploadSuccess	: function(data) {},
			onImageSelected : function(image_uri, el) {}
		},
			
		format: [ 
					'<div class="media-dialog" title="Media Editor">',
					'<div class="media-tabs">',
						'<ul>',
							'<li><a href="##{identifier}-upload-form">Upload</a></li>',
							'<li><a href="##{identifier}-media-library" class="media-gallery">Media Library</a></li>',
							'<li><a href="##{identifier}-media-editor" class="media-editor-tab-link">Media Editor</a></li>',
						'</ul>',
						'<div id="#{identifier}-upload-form">',
							'<form accept-charset="utf-8" action="#{uploadUrl}" method="post" enctype="multipart/form-data" class="UploadAdminAddForm">',
								'<input type="hidden" value="POST" name="_method">',
								'<div class="input file"><label for="UploadFile">File</label><input type="file" class="UploadFile" name="upload"></div>',
								'<div class="submit">',
									//'<input type="submit" value="Submit">',
									'<span id="media-upload-form-success"></span>',
								'</div>',
							'</form>',
						'</div>',
						'<div id="#{identifier}-media-library" class="media-library">',
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
						'<div id="#{identifier}-media-editor" class="media-editor">',
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
										'<span id="media_editor_status"></span>',
									'</div>',
								'</fieldset>',
							'</div>',
						'</div>',
					'</div>',
				'</div>'
			]
	};
	
	$.mediaCenter = {
		init: function(referrer, options) {
			$.mediaCenter.dialog = new MediaCenter.Dialog(referrer, options);
			return $.mediaCenter.dialog;
		}
	};
	
	$.fn.mediaCenter = function(options) {
		return $(this).each(function(index) {
			var me = this;
			
			$(this).click(function() {
				var dialog = $.mediaCenter.init(me, options);
				dialog.open();
			});
		});
	};
	
	
	
})(jQuery);
