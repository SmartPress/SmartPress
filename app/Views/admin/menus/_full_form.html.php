<?php $this->formFor(['admin', $this->menu], null, function($f) { ?>

	<?php if ($this->menu->errors && $this->menu->errors->count()): ?>
		<div id="error_explanation">
			<?php element('h2', "{$this->pluralize($this->menu, 'error')} prohibited this menu from beign saved:"); ?>
		</div>
		<ul>
			<?php $this->menu->errors->each(function($error) { ?>
				<li><?php echo $error; ?></li>
			<?php }); ?>
		</ul>
	<?php endif; ?>

	<div class="field">
		<?php $f->label("parent_id"); ?>
		<?php $f->collectionSelect("parent_id", $this->allMenus, 'id', 'title'); ?>
	</div>
	<div class="field">
		<?php $f->label("title"); ?>
		<?php $f->textField("title"); ?>
	</div>
	<div class="field">
		<?php $f->label("url"); ?>
		<?php $f->textField("url"); ?>
	</div>
	<div class="field">
		<?php $f->label("lft"); ?>
		<?php $f->textField("lft"); ?>
	</div>
	<div class="field">
		<?php $f->label("rght"); ?>
		<?php $f->textField("rght"); ?>
	</div>
	<div class="actions">
		<?php $f->submit('Save'); ?>
	</div>
	
<?php }); ?>
