<?php echo $this->formFor(['admin', $this->postcustomfield], ['class' => 'form-horizontal'], function($f) { ?>

	<?php if ($this->postcustomfield->errors && $this->postcustomfield->errors->count()): ?>
		<div id="error_explanation">
			<?php echo $this->element('h2', "{$this->pluralize($this->postcustomfield, 'error')} prohibited this postcustomfield from beign saved:"); ?>
		</div>
		<ul>
			<?php $this->postcustomfield->errors->each(function($error) { ?>
				<li><?php echo $error; ?></li>
			<?php }); ?>
		</ul>
	<?php endif; ?>
	<div class="control-group">
		<?php echo $f->label("field", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->textField("field"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $f->label("label", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->textField("label"); ?>
		</div>
	</div>
	<div class="form-actions actions">
		<?php echo $f->submit('Save', ['class' => 'btn btn-primary']); ?>
		<?php echo $this->linkTo('Back', $this->admin_post_custom_fields_url(), ['class' => 'btn']); ?>
	</div>
	
<?php }); ?>
