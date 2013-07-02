<p id="notice"></p>
<p>
	<b>Id</b>
	<?php echo $this->group->id; ?>
</p>
<p>
	<b>Name</b>
	<?php echo $this->group->name; ?>
</p>
<p>
	<b>Privileges</b>
	<?php echo $this->group->privilege; ?>
</p>
<?php echo $this->linkTo('Edit', $this->edit_group_path($this->group->id)); ?>
<?php echo $this->linkTo('Back', $this->groups_url()); ?>
