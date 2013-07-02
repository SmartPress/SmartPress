<?php echo $this->formFor(['admin', $this->menu], null, function($f) { ?>

	<?php if ($this->menu->errors && $this->menu->errors->count()): ?>
		<div id="error_explanation">
			<?php echo $this->element('h2', "{$this->pluralize($this->menu, 'error')} prohibited this menu from beign saved:"); ?>
		</div>
		<ul>
			<?php $this->menu->errors->each(function($error) { ?>
				<li><?php echo $error; ?></li>
			<?php }); ?>
		</ul>
	<?php endif; ?>

	<div class="field">
		<?php echo $f->label("parent_id"); ?>
		<?php echo $f->collectionSelect("parent_id", $this->allMenus, 'id', 'title'); ?>
	</div>
	<div class="field">
		<?php echo $f->label("title"); ?>
		<?php echo $f->textField("title"); ?>
	</div>
	<div class="field">
		<?php echo $f->label("url"); ?>
		<?php echo $f->textField("url"); ?>
	</div>
	<div class="field">
		<?php echo $f->label("lft"); ?>
		<?php echo $f->textField("lft"); ?>
	</div>
	<div class="field">
		<?php echo $f->label("rght"); ?>
		<?php echo $f->textField("rght"); ?>
	</div>
	<div class="actions">
		<?php echo $f->submit('Save'); ?>
	</div>
	
<?php }); ?>
