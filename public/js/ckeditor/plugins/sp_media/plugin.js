CKEDITOR.plugins.add('sp_media', {
	//icons: 'image',
	init: function(editor) {
		editor.addCommand('spMediaDialog', new CKEDITOR.dialogCommand('sp_mediaDialog'));
		editor.ui.addButton('Image', {
			label: 'Insert Image',
			command: 'spMediaDialog',
			toolbar: 'insert'
		});
		
		CKEDITOR.dialog.add('sp_mediaDialog', this.path + 'dialogs/sp_mediaDialog.js');
	}
});