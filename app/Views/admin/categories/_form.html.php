<?php $this->formFor($this->category, null, function($f) { ?>

	<?php if ($this->category->errors && $this->category->errors->count()): ?>
		<div id="error_explanation">
			<?php element('h2', "{$this->pluralize($this->category, 'error')} prohibited this category from beign saved:"); ?>
		</div>
		<ul>
			<?php foreach($this->category->errors as $error): ?>
				<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	<div class="field">
		<?php $f->label("id"); ?>
		<?php $f->textField("id"); ?>
	</div>
	<div class="field">
		<?php $f->label("parent_id"); ?>
		<?php $f->textField("parent_id"); ?>
	</div>
	<div class="field">
		<?php $f->label("lft"); ?>
		<?php $f->textField("lft"); ?>
	</div>
	<div class="field">
		<?php $f->label("rght"); ?>
		<?php $f->textField("rght"); ?>
	</div>
	<div class="field">
		<?php $f->label("name"); ?>
		<?php $f->textField("name"); ?>
	</div>
	<div class="field">
		<?php $f->label("slug"); ?>
		<?php $f->textField("slug"); ?>
	</div>
	<div class="field">
		<?php $f->label("created_at"); ?>
		<?php $f->textField("created_at"); ?>
	</div>
	<div class="field">
		<?php $f->label("updated_at"); ?>
		<?php $f->textField("updated_at"); ?>
	</div>
	<div class="actions">
		<?php $f->submit('Save'); ?>
	</div>
	
<?php }); ?>
