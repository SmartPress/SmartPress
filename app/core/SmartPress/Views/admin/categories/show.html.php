<p id="notice"></p>
<p>
	<b>Id</b>
	<?php echo $this->category->id; ?>
</p>
<p>
	<b>Parent</b>
	<?php echo $this->category->parent_id; ?>
</p>
<p>
	<b>Lft</b>
	<?php echo $this->category->lft; ?>
</p>
<p>
	<b>Rght</b>
	<?php echo $this->category->rght; ?>
</p>
<p>
	<b>Name</b>
	<?php echo $this->category->name; ?>
</p>
<p>
	<b>Slug</b>
	<?php echo $this->category->slug; ?>
</p>
<p>
	<b>Created At</b>
	<?php echo $this->category->created_at; ?>
</p>
<p>
	<b>Updated At</b>
	<?php echo $this->category->updated_at; ?>
</p>
<?php echo $this->linkTo('Edit', $this->edit_category_path($this->category->id)); ?>
<?php echo $this->linkTo('Back', $this->category_url()); ?>
