<?php echo $this->render('top-nav'); ?>

<section id="grid">
	<div class="page-header">
		<h1>Users</h1>
	</div>
	
	<table class="table table-striped">
		<tr>
			<th>Email</th>
			<th>Permissions</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		
		<?php $this->users->each(function($user) { ?>
			<tr>
				<td><?php echo $user->email; ?></td>
				<td><?php echo $user->permissions; ?></td>
				<td><?php echo $this->linkTo('<i class="icon-eye-open"></i> Show', $this->admin_user_path($user->id), ['class' => 'btn']); ?></td>
				<td><?php echo $this->linkTo('<i class="icon-edit icon-white"></i> Edit', $this->edit_admin_user_path($user->id), ['class' => 'btn btn-primary']); ?></td>
				<td><?php echo $this->linkTo('<i class="icon-trash icon-white"></i> Destroy', $this->admin_user_path($user->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete', 'class' => 'btn btn-danger' )); ?></td>
			</tr>
		<?php }); ?>
	</table>
</section>
