<?php echo $this->render('top-nav'); ?>

<section id="grid">
	<div class="page-header">
		<h1>Groups</h1>
	</div>
	
	<table class="table table-striped">
		<tr>
			<th>Name</th>
			<th>Privilege</th>
			<th></th>
			<th></th>
		</tr>
		
		<?php $this->groups->each(function($group) { ?>
			<tr>
				<td><?php echo $group->name; ?></td>
				<td><?php echo $group->privilege; ?></td>
				<td><?php echo $this->linkTo('<i class="icon-edit icon-white"></i> Edit', $this->edit_admin_group_path($group->id), ['class' => 'btn btn-primary']); ?></td>
				<td><?php echo $this->linkTo('<i class="icon-trash icon-white"></i> Destroy', $this->admin_group_path($group->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete', 'class' => 'btn btn-danger' )); ?></td>
			</tr>
		<?php }); ?>
	</table>
</section>
