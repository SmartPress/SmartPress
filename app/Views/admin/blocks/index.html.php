<h1>Listing blocks</h1>

<table>
	<tr>
		<th>Id</th>
		<th>Path</th>
		<th>Block</th>
		<th>Element</th>
		<th>Params</th>
		<th>Priority</th>
		<th></th>
		<th></th>
		<th></th>
	</tr>
	
	<?php $this->blocks->each(function($block) { ?>
		<tr>
			<td><?php echo $block->id; ?></td>
			<td><?php echo $block->path; ?></td>
			<td><?php echo $block->block; ?></td>
			<td><?php echo $block->element; ?></td>
			<td><?php echo $block->params; ?></td>
			<td><?php echo $block->priority; ?></td>
			<td><?php $this->linkTo('Show', $this->admin_block_path($block->id)); ?></td>
			<td><?php $this->linkTo('Edit', $this->edit_admin_block_path($block->id)); ?></td>
			<td><?php $this->linkTo('Destroy', $this->admin_block_path($block->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete' )); ?></td>
		</tr>
	<?php }); ?>
</table>
			
<br>
			
<?php $this->linkTo("New blocks", $this->new_admin_block_path()); ?>