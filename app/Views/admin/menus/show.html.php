<p id="notice"></p>
<p>
	<b>Id</b>
	<?php echo $this->menu->id; ?>
</p>
<p>
	<b>Parent</b>
	<?php echo $this->menu->parent_id; ?>
</p>
<p>
	<b>Lft</b>
	<?php echo $this->menu->lft; ?>
</p>
<p>
	<b>Rght</b>
	<?php echo $this->menu->rght; ?>
</p>
<p>
	<b>Title</b>
	<?php echo $this->menu->title; ?>
</p>
<p>
	<b>Url</b>
	<?php echo $this->menu->url; ?>
</p>
<p>
	<b>Created At</b>
	<?php echo $this->menu->created_at; ?>
</p>
<p>
	<b>Updated At</b>
	<?php echo $this->menu->updated_at; ?>
</p>
<?php echo $this->linkTo('Edit', $this->edit_menu_path($this->menu->id)); ?>
<?php echo $this->linkTo('Back', $this->menus_url()); ?>
