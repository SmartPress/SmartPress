<?php echo $this->render('top-nav'); ?>

<section id="grid">
	<div class="page-header">
		<h1>Blocks</h1>
	</div>
	
	<table class="table table-striped">
		<tr>
			<th>Id</th>
			<th>Path</th>
			<th>Block</th>
			<th>Element</th>
			<th>Priority</th>
			<th></th>
			<th></th>
		</tr>
		
		<?php $this->blocks->each(function($block) { ?>
			<tr>
				<td><?php echo $block->id; ?></td>
				<td><?php echo $block->path; ?></td>
				<td><?php echo $block->block; ?></td>
				<td><?php echo $block->element; ?></td>
				<td><?php echo $block->priority; ?></td>
				<td><?php echo $this->linkTo('<i class="icon-edit icon-white"></i> Edit', $this->edit_admin_block_path($block->id), ['class' => 'btn btn-primary']); ?></td>
				<td><?php echo $this->linkTo('<i class="icon-trash icon-white"></i> Destroy', $this->admin_block_path($block->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete', 'class' => 'btn btn-danger' )); ?></td>
			</tr>
		<?php }); ?>
	</table>
</section>
