<?php echo $this->render('top-nav'); ?>

<section id="grid">
	<div class="page-header">
		<h1>Custom Fields</h1>
	</div>

	<table class="table table-striped">
		<tr>
			<th>Field</th>
			<th>Label</th>
			<th></th>
			<th></th>
		</tr>
		
		<?php $this->postcustomfields->each(function($postcustomfield) { ?>
			<tr>
				<td><?php echo $postcustomfield->field; ?></td>
				<td><?php echo $postcustomfield->label; ?></td>
				<td><?php echo $this->linkTo('<i class="icon-edit icon-white"></i> Edit', $this->edit_admin_post_custom_field_path($postcustomfield->id), ['class' => 'btn btn-primary']); ?></td>
				<td><?php echo $this->linkTo('<i class="icon-trash icon-white"></i> Destroy', $this->admin_post_custom_field_path($postcustomfield->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete', 'class' => 'btn btn-danger' )); ?></td>
			</tr>
		<?php }); ?>
	</table>
</section>
