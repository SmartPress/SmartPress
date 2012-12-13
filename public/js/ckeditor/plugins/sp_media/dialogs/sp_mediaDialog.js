
CKEDITOR.dialog.add('sp_mediaDialog', function(editor) {
	return {
		title	: "Media Dialog",
		minWidth	: 400,
		minHeight	: 300,
		resizable	: CKEDITOR.DIALOG_RESIZE_BOTH,
		
		ALIGN_NONE	: '0',
		ALIGN_LEFT	: '1',
		ALIGN_CENTER: '2',
		ALIGN_RIGHT	: '3',
		
		contents	: [{
			label	: 'Upload',
			title	: 'Upload',
			id		: 'upload-tab',
			elements: [{
				type: 'file',
				id	: 'upload',
				label	: editor.lang.image.btnUpload,
				style	: 'height: 40px',
				size	: 38
			},{
				type: 'fileButton',
				id	: 'uploadButton',
				//filebrowser	: 'info:txtUrl',
				filebrowser	: {
					action	: 'QuickUpload',
					onSelect: function(fileUrl, data) {
						console.log([data, fileUrl]);
						var dialog	= this.getDialog(),
							message	= data.message;
						
						dialog
							.getContentElement('upload-tab','upload_status')
							.getElement()
							.setText(message);
						
						return false;
					}
				},
				label	: editor.lang.image.btnUpload,
				'for'	: ['upload-tab','upload']
			},{
				type: 'html',
				html: '',
				id	: 'upload_status'
			}]
		},{
			label	: 'Add Image',
			title	: 'Add Image',
			id		: 'add-image-tab',
			elements: [{
				type: 'html',
				html: '<table id="images-selection" width="100%" style="margin:0;width:100%;">' +
						'<tr>' +
							'<th>Thumb</th>' + 
							'<th>Details</th>' +
							'<th>Actions</th>' +
						'</tr>' +
					'</table>',
				id	: 'image-select'
			}]
		},{
			label	: 'Editor',
			title	: 'Editor',
			id		: 'edit-image-tab',
			elements: [{
				type: 'hbox',
				widths	: ['%33', '%33', '%33'],
				children: [{
					type: 'html',
					html: '<div class="heading">Original</div>' +
						'<div><label>Width:</label> <span id="media_editor_orig_width"></span></div>' +
						'<div><label>Heigh:</label> <span id="media_editor_orig_height"></span></div>'
				},{
					type: 'html',
					html: '<div class="heading">Scale</div>' +
					'<div class="checkbox"><label>Lock</label> <input type="checkbox" name="lock" class="media-editor-scale-lock" checked="checked" /></div>' +
					'<div><label>Width:</label> <input type="text" name="scaleWidth" id="media_editor_scale_width" /></div>' +
					'<div><label>Height:</label> <input type="text" name="scaleHeight" id="media_editor_scale_height" /></div>'
				},{
					type: 'html',
					html: ''
				}]
			},{
				type: 'html',
				html: '<img src="" id="media_editor_image" />',
				id	: 'image-html'
			}]
		}],
		
		onLoad	: function() {
			this.on('selectPage', this.definition.onSelectPage, this);
		},
		
		onShow	: function() {
			this.hidePage('edit-image-tab');
			$('tr.insert_image_dialog').remove();
		},
		
		/**
		 * Switching page action
		 */
		onSelectPage	: function(evt) {
			if (evt.data.page == 'add-image-tab')
				this.definition.onAddPageSelect.call(this, evt);
			
			if (evt.data.page == 'edit-image-tab')
				this.definition.onEditorPageSelect.call(this, evt);
		},
		
		/**
		 * Editor page selected
		 */
		onEditorPageSelect	: function(evt) {
			console.log('Editor page select');
		},
		
		/**
		 * Add image page selected
		 */
		onAddPageSelect	: function(evt) {	
			if (this.definition.images.length > 0) 
				return true;
			
			var me	= this; 
			$.getJSON('/admin/uploads.json', function(data) {
				var images	= me.definition.images = data.files,
					dialog	= me,
					//el	= dialog.getContentElement('add-image-tab', 'image-select').getElement(),
					tpl	= me.definition.imageTpl.join("\n");
				
				$("#images-selection .image-thumb").remove();
				for (var i = 0; i < images.length; i++) {
					var image = images[i], newEntry;
					
					newEntry	= tpl.replace(/\{\$src\}/ig, image.filename);
					newEntry	= newEntry.replace(/\{\$width\}/ig, image.width);
					newEntry	= newEntry.replace(/\{\$height\}/ig, image.height);
					newEntry	= newEntry.replace(/\{\$id\}/ig, image.id);
					$("#images-selection").append(newEntry);
				}
				
				$('.media-library-image-link').on('click', function(evt) {
					me.definition.onMediaImageLinkClick.call(me, $(this), evt);
				});
				
				$('#media_editor_link').on('click', function(evt) {
					me.definition.onEditorLinkClick.call(me, $(this), evt);
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
		 * Insert into post action
		 */
		onInsertButtonClick: function(el, evt) {
			var img = editor.document.createElement('img');
			img.setAttribute('title', $('#insert_image_title').val());
			img.setAttribute('alt', $('#insert_image_alternate_text').val());
			img.setAttribute('src', $('#insert_image_link_url').val());
			img.setAttribute('class', $('input[name="insert_image_align"]:checked').val());
			
			editor.insertElement(img);
			this.hide();
		},
		
		/**
		 * Send to editor link action
		 */
		onEditorLinkClick: function(el, evt) {
			this.showPage('edit-image-tab');
			this.selectPage('edit-image-tab');
			$('#media_editor_image').attr('src', el.data('src'));
			
			$('#media_editor_orig_width').text(el.data('width'));
			$('#media_editor_orig_height').text(el.data('height'));
			
			$('#media_editor_scale_width').val(el.data('width'));
			$('#media_editor_scale_height').val(el.data('height'));
		},
		
		images	: [],
		
		imageTpl: [
		           '<tr class="image-thumb" data-id="{$id}">',
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
				
		insertTpl	: [
		         '<tr class="insert_image_dialog" style="display: none;">',
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
         					/*'<tr>',
         						'<td width="33%"><strong>Size</strong></td>',
         						'<td width="67%">',
         							'',
         						'</td>',
         					'</tr>',*/
         					'<tr>',
         						'<td width="33%">&nbsp;</td>',
         						'<td width="67%">',
         							'<a href="javascript:void(0)" style="-webkit-user-select:none;" role="button" class="cke_dialog_ui_fileButton cke_dialog_ui_button" id="insert_image_into_post">',
         								'<span class="cke_dialog_ui_button">Insert Into Post</span>',
         							'</a>',
         						'</td>',
         					'</tr>',
		         		'</table>',
		         	'</td>',
		         '</tr>'
		]
	};
});

// OLD DEF
/*{
	id		: 'insert-image',
	label	: 'Insert Image',
	filebrowser	: 'uploadButton',
	elements: [{
		type: 'file',
		id	: 'upload',
		label	: editor.lang.image.btnUpload,
		style	: 'height: 40px',
		size	: 38
	},{
		type: 'fileButton',
		id	: 'uploadButton',
		//filebrowser	: 'info:txtUrl',
		filebrowser	: {
			action	: 'QuickUpload',
			onSelect: function(fileUrl, data) {
				console.log([data, fileUrl]);
				var dialog	= this.getDialog();
				console.log(dialog.getContentElement('tab1','upload_status'));
				
				return false;
			}
		},
		label	: editor.lang.image.btnUpload,
		'for'	: ['insert-image','upload']
	},{
		type: 'html',
		html: '',
		id	: 'upload_status'
	}]
}*/