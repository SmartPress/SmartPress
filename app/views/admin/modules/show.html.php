<p id="notice"></p>
<p>
	<b>Id</b>
	<?php echo $this->module->id; ?>
</p>
<p>
	<b>Name</b>
	<?php echo $this->module->name; ?>
</p>
<p>
	<b>Code</b>
	<?php echo $this->module->code; ?>
</p>
<p>
	<b>Version</b>
	<?php echo $this->module->version; ?>
</p>
<p>
	<b>Status</b>
	<?php echo $this->module->status; ?>
</p>
<?php echo $this->linkTo('Edit', $this->edit_module_path($this->module->id)); ?>
<?php echo $this->linkTo('Back', $this->modules_url()); ?>