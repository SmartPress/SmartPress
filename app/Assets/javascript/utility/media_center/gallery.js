(function($) {
	if (window.MediaCenter == null)
		window.MediaCenter = {};
	
	/**
	 * Class for media gallery functions
	 */
	window.MediaCenter.Gallery	= function(dialog) {
		this.dialog = dialog;
		this.deleteUri	= this.dialog.options.deleteUri;
		this.libraryUri	= this.dialog.options.libraryUri;
		this.ckeditor 	= this.dialog.options.ckeditor;
		
		return this;
	};
	
	window.MediaCenter.Gallery.prototype = {
		images	: [],
		
		getDialog: function() {
			return this.dialog;
		},
		
		deleteImage: function(el) {
			var	id = el.data('id'),
				deleteUrl	= this.deleteUrl(id);
			
			$.ajax({
				url	: deleteUrl,
				dataType: 'json',
				data: {
					_method: 'DELETE'
				},
				type: "POST",
				success: function(data, textStatus, jqXHR) {
					if (data.success) {
						$('.image-row[data-id="' + id + '"]').remove();
					}
				}
			});
			
			return false;
		},
		
		deleteUrl: function(id) {
			var url = this.deleteUri;
			url = url.replace(/#\{id\}/ig, id);
			return url;
		},
		
		showInsert: function(after) {
			var me	= this,
				tpl	= this.insertTpl.join("\n"),
				currentDialog	= $('tr.insert_image_dialog');
			
			currentDialog.remove();
			after.after(tpl);
			//$('tr.insert_image_dialog').slideDown();
			
		},
		
		/**
		 * Choose thumb link action
		 */
		onMediaImageLinkClick: function(el, evt) {
			var id = el.data('id'),
				cont = $('tr.image-row[data-id="' + id + '"]'),
				me	= this;
			
			this.getDialog().clearButtons();
			if (this.ckeditor) {
				this.showInsert(cont);
				$('#insert_image_link_url').val(el.data('src'));
				
				/*$('#insert_image_into_post').button().click(function(evt) {
					me.onInsertButtonClick.call(me, el, evt);
				});*/
				
				this.getDialog().addButtons([{
					text: "Insert",
					click: function() {
						me.onInsertButtonClick.call(me);
					}
				},{
					text: "Cancel",
					click: function() {
						me.close();
					}
				}]);
			} else {
				this.referrer.val(el.data('src'));
				this.close();
			}
		},
		
		onInsertButtonClick: function() {
			var editor = this.getDialog().referrer,
				container = editor.document.createElement('div'),
				img	= editor.document.createElement('img');
			
			container.setAttribute('class', 'image-container ' + 
					this.getSelectedAlignment().val() + ' ' +
					this.getSelectedSize().val()
			);
			img.setAttribute('title', this.getInsertImageTitle().val());
			img.setAttribute('alt', this.getInsertImageAlt().val());
			img.setAttribute('src', this.getInsertImageSrc().val());
			container.append(img);
			
			editor.insertElement(container);
			this.getDialog().close();
		},
		
		onShow: function() {
			if (this.images.length > 0) 
				return true;
			
			this.load();
		},
		
		load: function() {
			var me	= this; 
			$.getJSON(this.libraryUri, function(data) {
				var images	= me.images = data.files,
					//el	= dialog.getContentElement('add-image-tab', 'image-select').getElement(),
					tpl	= me.imageTpl.join("\n");
				
				$("#images-selection .image-row").remove();
				for (var i = 0; i < images.length; i++) {
					var image = images[i], newEntry;
					
					newEntry	= tpl.replace(/#\{src\}/ig, image.filename);
					newEntry	= newEntry.replace(/#\{width\}/ig, image.width);
					newEntry	= newEntry.replace(/#\{height\}/ig, image.height);
					newEntry	= newEntry.replace(/#\{id\}/ig, image.id);
					$("#images-selection").append(newEntry);
				}
				
				$('.media-library-image-link').click(function(evt) {
					me.onMediaImageLinkClick.call(me, $(this), evt);
				});
				
				$('.media-editor-link').click(function(evt) {
					me.getDialog().getEditor().edit($(this));
				});
				
				$('.media-editor-delete-link').click(function(evt) {
					return me.deleteImage($(this));
				});
			});
		},
		
		getSelectedAlignment: function() {
			return $('input[name="insert_image_align"]:checked');
		},
		
		getSelectedSize: function() {
			return $('input[name="insert_image_size"]:checked');
		},
		
		getInsertImageTitle: function() {
			return $('#insert_image_title');
		},
		
		getInsertImageSrc: function() {
			return $('#insert_image_link_url');
		},
		
		getInsertImageAlt: function() {
			return $('#insert_image_alternate_text');
		},
		
		imageTpl: [
		           '<tr class="image-row" data-id="#{id}">',
		           		'<td>',
			           		'<a href="javascript:void(0)" class="media-library-image-link" data-id="#{id}" data-src="/uploads/#{src}"><img src="/uploads/#{src}" width="75" height="50" border="0" class="media-library-image" style="width:75px;height:50px;" /></a><br/>',
			           	'</td>',
			           	'<td>',
			           		'Width: #{width}<br />',
			           		'Height: #{height}<br />',
			           	'</td>',
			           	'<td>',
			           		'<a href="javascript:void(0)" class="media-editor-link" data-src="/uploads/#{src}" data-id="#{id}" data-width="#{width}" data-height="#{height}">Send to Editor</a><br />',
			           		'<a href="javascript:void(0)" class="media-editor-delete-link" data-id="#{id}">Delete</a><br />',
			           	'</td>',
					'</tr>'
		],
		
		insertTpl	: [
				         '<tr class="insert_image_dialog">',
				         	'<td colspan="3">',
				         		'<table width="100%" style="width:100%;">',
				         			'<tr>',
				         				'<td width="33%"><strong>Title</strong></td>',
				         				'<td width="67%">',
				         					'<input type="text" id="insert_image_title" style="width: 95%;" />',
				         				'</td>',
				         			'</tr>',
				         			'<tr>',
			         					'<td width="33%"><strong>Alternate Text</strong></td>',
			         					'<td width="67%">',
			         						'<input type="text" id="insert_image_alternate_text" style="width: 95%;" />',
			         					'</td>',
			         				'</tr>',
			         				'<tr>',
		         						'<td width="33%"><strong>Link URL</strong></td>',
		         						'<td width="67%">',
		         							'<input type="text" id="insert_image_link_url" style="width: 95%;" />',
		         						'</td>',
		         					'</tr>',
		         					'<tr>',
		         						'<td width="33%"><strong>Alignment</strong></td>',
		         						'<td width="67%" style="padding-bottom: 9px;">',
		         							'<input type="radio" name="insert_image_align" value="" checked="checked" /> None&nbsp;&nbsp;',
		         							'<input type="radio" name="insert_image_align" value="image-align-left" /> Left&nbsp;&nbsp;',
		         							'<input type="radio" name="insert_image_align" value="image-align-center" /> Center&nbsp;&nbsp;',
		         							'<input type="radio" name="insert_image_align" value="image-align-right" /> Right&nbsp;&nbsp;',
		         						'</td>',
		         					'</tr>',
		         					'<tr>',
		         						'<td width="33%"><strong>Size</strong></td>',
		         						'<td width="67%">',
		         							'<input type="radio" name="insert_image_size" value="" checked="checked" /> Full Size&nbsp;&nbsp;',
		         							'<input type="radio" name="insert_image_size" value="image-thumbnail" /> Thumbnail&nbsp;&nbsp;',
		         							'<input type="radio" name="insert_image_size" value="image-medium" /> Medium&nbsp;&nbsp;',
		         							'<input type="radio" name="insert_image_size" value="image-large" /> Large&nbsp;&nbsp;',
		         						'</td>',
		         					'</tr>',
				         		'</table>',
				         	'</td>',
				         '</tr>'
				]
		
	};
})(jQuery);