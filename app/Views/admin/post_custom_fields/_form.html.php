<?php $this->formFor(['admin', $this->postcustomfield], ['class' => 'form-horizontal'], function($f) { ?>

	<?php if ($this->postcustomfield->errors && $this->postcustomfield->errors->count()): ?>
		<div id="error_explanation">
			<?php element('h2', "{$this->pluralize($this->postcustomfield, 'error')} prohibited this postcustomfield from beign saved:"); ?>
		</div>
		<ul>
			<?php $this->postcustomfield->errors->each(function($error) { ?>
				<li><?php echo $error; ?></li>
			<?php }); ?>
		</ul>
	<?php endif; ?>
	<div class="control-group">
		<?php $f->label("field", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php $f->textField("field"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php $f->label("label", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php $f->textField("label"); ?>
		</div>
	</div>
	<div class="form-actions actions">
		<?php $f->submit('Save', ['class' => 'btn btn-primary']); ?>
		<?php $this->linkTo('Back', $this->admin_post_custom_fields_url(), ['class' => 'btn']); ?>
	</div>
	
<?php }); ?>