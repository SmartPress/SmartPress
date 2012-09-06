<?php $this->formFor(['admin', $this->postcustomfield], null, function($f) { ?>

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
	<div class="field">
		<?php $f->label("field"); ?>
		<?php $f->textField("field"); ?>
	</div>
	<div class="field">
		<?php $f->label("label"); ?>
		<?php $f->textField("label"); ?>
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