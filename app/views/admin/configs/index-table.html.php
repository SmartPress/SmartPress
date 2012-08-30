<?php $this->render('top-nav'); ?>

<section id="grid">
	<div class="page-header">
		<h1>Settings</h1>
	</div>
	
	<table class="table table-striped">
		<tr>
			<th>Name</th>
			<th>Value</th>
			<th></th>
			<th></th>
		</tr>
		
		<?php $this->configs->each(function($config) { ?>
			<tr>
				<td><?php echo $config->name; ?></td>
				<td><?php echo $config->value; ?></td>
				<td><?php $this->linkTo('<i class="icon-edit icon-white"></i> Edit', $this->edit_admin_config_path($config->id), array('class' => 'btn btn-primary')); ?></td>
				<td><?php $this->linkTo('<i class="icon-trash icon-white"></i> Destroy', $this->admin_config_path($config->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete', 'class' => 'btn btn-danger' )); ?></td>
			</tr>
		<?php }); ?>
	</table>
</section>