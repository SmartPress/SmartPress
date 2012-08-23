<p id="notice"></p>
<p>
	<b>Id</b>
	<?php echo $this->user->id; ?>
</p>
<p>
	<b>Username</b>
	<?php echo $this->user->username; ?>
</p>
<p>
	<b>Password</b>
	<?php echo $this->user->password; ?>
</p>
<p>
	<b>Group</b>
	<?php echo $this->user->group_id; ?>
</p>
<p>
	<b>Created At</b>
	<?php echo $this->user->created_at; ?>
</p>
<p>
	<b>Updated At</b>
	<?php echo $this->user->updated_at; ?>
</p>
<?php echo $this->linkTo('Edit', $this->edit_admin_user_path($this->user->id)); ?>
<?php echo $this->linkTo('Back', $this->admin_users_url()); ?>