<h1>Listing groups</h1>

<table>
	<tr>
		<th>Id</th>
		<th>Name</th>
		<th>Read Privileges</th>
		<th>Write Privileges</th>
		<th></th>
		<th></th>
		<th></th>
	</tr>
	
	<?php $this->groups->each(function($group) { ?>
		<tr>
			<td><?php echo $group->id; ?></td>
			<td><?php echo $group->name; ?></td>
			<td><?php echo $group->read_privileges; ?></td>
			<td><?php echo $group->write_privileges; ?></td>
			<td><?php $this->linkTo('Show', $this->group_path($group->id)); ?></td>
			<td><?php $this->linkTo('Edit', $this->edit_group_path($group->id)); ?></td>
			<td><?php $this->linkTo('Destroy', $this->group_path($group->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete' )); ?></td>
		</tr>
	<?php }); ?>
</table>
			
<br>
			
<?php $this->linkTo("New groups", $this->new_group_path()); ?>