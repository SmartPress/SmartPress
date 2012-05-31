<p id="notice"></p>
<p>
	<b>Id</b>
	<?php echo $this->config->id; ?>
</p>
<p>
	<b>Name</b>
	<?php echo $this->config->name; ?>
</p>
<p>
	<b>Value</b>
	<?php echo $this->config->value; ?>
</p>
<?php echo $this->linkTo('Edit', $this->edit_admin_config_path($this->config->id)); ?>
<?php echo $this->linkTo('Back', $this->admin_configs_url()); ?>