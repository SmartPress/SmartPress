<p id="notice"></p>
<p>
	<b>Id</b>
	<?php echo $this->block->id; ?>
</p>
<p>
	<b>Path</b>
	<?php echo $this->block->path; ?>
</p>
<p>
	<b>Block</b>
	<?php echo $this->block->block; ?>
</p>
<p>
	<b>Element</b>
	<?php echo $this->block->element; ?>
</p>
<p>
	<b>Params</b>
	<?php echo $this->block->params; ?>
</p>
<p>
	<b>Priority</b>
	<?php echo $this->block->priority; ?>
</p>
<?php echo $this->linkTo('Edit', $this->edit_admin_block_path($this->block->id)); ?>
<?php echo $this->linkTo('Back', $this->admin_blocks_url()); ?>
