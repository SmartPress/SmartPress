<p id="notice"></p>
<p>
	<b>Id</b>
	<?php echo $this->postcustomfield->id; ?>
</p>
<p>
	<b>Field</b>
	<?php echo $this->postcustomfield->field; ?>
</p>
<p>
	<b>Label</b>
	<?php echo $this->postcustomfield->label; ?>
</p>
<p>
	<b>Created At</b>
	<?php echo $this->postcustomfield->created_at; ?>
</p>
<p>
	<b>Updated At</b>
	<?php echo $this->postcustomfield->updated_at; ?>
</p>
<?php echo $this->linkTo('Edit', $this->edit_postcustomfield_path($this->postcustomfield->id)); ?>
<?php echo $this->linkTo('Back', $this->postcustomfields_url()); ?>
