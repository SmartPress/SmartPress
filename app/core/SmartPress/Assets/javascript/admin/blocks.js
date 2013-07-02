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
				},
				redirect: window.location.pathname
			};
		
		$.getJSON('/admin/blocks/new_with_ns.json', data, function(data) {
			$('#block_form').html(data.template).slideDown();
			$('#block_form #block_element').val($('#new_block_select option:selected').val());
			
			$('#new_block_save_btn').click(function() {
				//console.log($("#new_block form").serialize());
				
				$("#new_block form").submit();
			});
		}).error(function(error) {
			$('#block_form').text('Unknown error occured!').slideDown();
		});
	});
	
	
	$('#block_form #block_element').change(function() {
		$('#block_params_container').slideUp();
		var selected = $('option:selected', this).val();
		if (selected.length < 1) return false;
		
		var me	= $(this),
			data= {
				block: selected
			};
		
		$.getJSON('/admin/blocks/fields.json', data, function(data) {
			$('#block_params_container').html(data.template).slideDown();
		}).error(function(error) {
			$('#block_params_container').text('Unknown error occured!').slideDown();
		});
	});
	
});