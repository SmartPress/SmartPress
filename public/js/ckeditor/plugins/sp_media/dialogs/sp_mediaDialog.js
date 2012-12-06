
CKEDITOR.dialog.add('sp_mediaDialog', function(editor) {
	return {
		title: "Media Dialog",
		minWidth	: 400,
		minHeight	: 300,
		contents	: [{
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
				filebrowser	: 'info:txtUrl',
				label	: editor.lang.image.btnUpload,
				'for'	: ['insert-image','upload']
			}]
		}]
	};
});