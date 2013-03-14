<?php echo $this->formFor(['admin', $this->category], ['class' => 'form-horizontal'], function($f) { ?>

	<?php if ($this->category->errors && $this->category->errors->count()): ?>
		<div id="error_explanation">
			<?php echo $this->element('h2', "{$this->pluralize($this->category, 'error')} prohibited this category from beign saved:"); ?>
		</div>
		<ul>
			<?php foreach($this->category->errors as $error): ?>
				<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<div class="control-group">
		<?php echo $f->label("parent_id", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->collectionSelect("parent_id", \SmartPress\Models\Category::options(), 'id', 'name'); ?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $f->label("name", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->textField("name"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $f->label("slug", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->textField("slug"); ?>
		</div>
	</div>
	<div class="form-actions">
		<?php echo $this->linkTo('Back', $this->admin_categories_url(), ['class' => 'btn']); ?>
		<?php echo $f->submit('Save', ['class' => 'btn btn-primary']); ?>
	</div>
	
<?php }); ?>
