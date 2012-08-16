<h1>Listing users</h1>

<table>
	<tr>
		<th>Id</th>
		<th>Username</th>
		<th>Password</th>
		<th>Group</th>
		<th>Created At</th>
		<th>Updated At</th>
		<th></th>
		<th></th>
		<th></th>
	</tr>
	
	<?php $this->users->each(function($user) { ?>
		<tr>
			<td><?php echo $user->id; ?></td>
			<td><?php echo $user->username; ?></td>
			<td><?php echo $user->password; ?></td>
			<td><?php echo $user->group_id; ?></td>
			<td><?php echo $user->created_at; ?></td>
			<td><?php echo $user->updated_at; ?></td>
			<td><?php $this->linkTo('Show', $this->admin_user_path($user->id)); ?></td>
			<td><?php $this->linkTo('Edit', $this->edit_admin_user_path($user->id)); ?></td>
			<td><?php $this->linkTo('Destroy', $this->admin_user_path($user->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete' )); ?></td>
		</tr>
	<?php }); ?>
</table>
			
<br>
			
<?php $this->linkTo("New users", $this->new_admin_user_path()); ?>