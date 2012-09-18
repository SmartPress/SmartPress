$(document).ready(function() {
	
	$('#new_block_select').change(function() {
		$('#block_form').hide();
		var selected = $('option:selected', this).val();
		if (selected.length < 1) return false;
		
		var me	= $(this),
			data= { 
				block: selected,
				scope: {
					controller: me.data('controller'),
					action	: me.data('action')
				}
			};
		
		$.getJSON('/admin/blocks/new_with_ns.json', data, function(data) {
			$('#block_form').html(data.template).slideDown();
			
			$('#new_block_save_btn').click(function() {
				//console.log($("#new_block form").serialize());
				
				$("#new_block form").submit();
			});
		}).error(function(error) {
			$('#block_form').text('Unknown error occured!').slideDown();
		});
	});
	
});