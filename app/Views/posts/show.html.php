<p id="notice"></p>

<p>
	<b>Id</b>
	<?php echo $this->post->id; ?>
</p>
<p>
	<b>Title</b>
	<?php echo $this->post->title; ?>
</p>
<p>
	<b>Content</b>
	<?php echo $this->post->content; ?>
</p>
<p>
	<b>Custom Data</b>
	<?php echo $this->post->custom_data; ?>
</p>
<p>
	<b>Type</b>
	<?php echo $this->post->type; ?>
</p>
<p>
	<b>Slug</b>
	<?php echo $this->post->slug; ?>
</p>
<p>
	<b>Layout</b>
	<?php echo $this->post->layout; ?>
</p>
<p>
	<b>Status</b>
	<?php echo $this->post->status; ?>
</p>
<p>
	<b>Created At</b>
	<?php echo $this->post->created_at; ?>
</p>
<p>
	<b>Updated At</b>
	<?php echo $this->post->updated_at; ?>
</p>
<p><?php echo $this->post->html; ?></p>