<?php $this->render('top-nav'); ?>

<section>
	<div class="page-header">
		<h1>Modules</h1>
		<?php if ($this->session->has('flash')): ?>
			<?php foreach ($this->session->get('flash') as $type => $message): ?>
				<div class="alert alert-<?php echo $type; ?> fade in">
					<button type="button" class="close" data-dismiss="alert">x</button>
					<strong><?php echo ucwords($type); ?></strong> - <?php echo $message; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	
	<table class="table table-striped">
		<tr>
			<th>Name</th>
			<th>Code</th>
			<th>Version</th>
			<th>Status</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		
		<?php $this->modules->each(function($module) { ?>
			<tr>
				<td><?php echo $module->name; ?></td>
				<td><?php echo $module->code; ?></td>
				<td><?php echo $module->version; ?></td>
				<td><?php echo $module->status; ?></td>
				<td><?php $this->linkTo('<i class="icon-eye-open"></i> Show', $this->admin_module_path($module->id), array('class' => 'btn')); ?></td>
				<td><?php $this->linkTo('<i class="icon-repeat icon-white"></i> Update', $this->edit_admin_module_path($module->id), array('class' => 'btn btn-warning')); ?></td>
				<td><?php $this->linkTo('<i class="icon-trash icon-white"></i> Destroy', $this->admin_module_path($module->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete', 'class' => 'btn btn-danger' )); ?></td>
			</tr>
		<?php }); ?>
	</table>
</section>