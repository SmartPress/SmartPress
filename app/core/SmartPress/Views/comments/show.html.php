<p id="notice"></p>
<p>
	<b>Id</b>
	<?php echo $this->comment->id; ?>
</p>
<p>
	<b>Post</b>
	<?php echo $this->comment->post_id; ?>
</p>
<p>
	<b>User</b>
	<?php echo $this->comment->user_id; ?>
</p>
<p>
	<b>Content</b>
	<?php echo $this->comment->content; ?>
</p>
<p>
	<b>Author</b>
	<?php echo $this->comment->author; ?>
</p>
<p>
	<b>Author Email</b>
	<?php echo $this->comment->author_email; ?>
</p>
<p>
	<b>Author Url</b>
	<?php echo $this->comment->author_url; ?>
</p>
<p>
	<b>Author Ip</b>
	<?php echo $this->comment->author_ip; ?>
</p>
<p>
	<b>Status</b>
	<?php echo $this->comment->status; ?>
</p>
<p>
	<b>Created At</b>
	<?php echo $this->comment->created_at; ?>
</p>
<p>
	<b>Updated At</b>
	<?php echo $this->comment->updated_at; ?>
</p>
<?php echo $this->linkTo('Edit', $this->edit_comment_path($this->comment->id)); ?>
<?php echo $this->linkTo('Back', $this->comments_url()); ?>
