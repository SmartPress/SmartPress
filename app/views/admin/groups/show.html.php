<p id="notice"></p>

	<b>Id</b>
	<?php echo $this->group->id; ?>
</p>
<p>
	<b>Name</b>
	<?php echo $this->group->name; ?>
</p>
<p>
	<b>Read Privileges</b>
	<?php echo $this->group->read_privileges; ?>
</p>
<p>
	<b>Write Privileges</b>
	<?php echo $this->group->write_privileges; ?>
</p>

<?php echo $this->linkTo('Back', $this->groups_url()); ?>