<?php $this->formFor(['admin', $this->block], null, function($f) { ?>

	<?php if ($this->block->errors && $this->block->errors->count()): ?>
		<div id="error_explanation">
			<?php element('h2', "{$this->pluralize($this->block, 'error')} prohibited this block from beign saved:"); ?>
		</div>
		<ul>
			<?php $this->block->errors->each(function($error) { ?>
				<li><?php echo $error; ?></li>
			<?php }); ?>
		</ul>
	<?php endif; ?>
	<div class="field">
		<?php $f->label("path"); ?>
		<?php $f->textField("path"); ?>
	</div>
	<div class="field">
		<?php $f->label("block"); ?>
		<?php $f->textField("block"); ?>
	</div>
	<div class="field">
		<?php $f->label("element"); ?>
		<?php $f->textField("element"); ?>
	</div>
	<div class="field">
		<?php $f->label("params"); ?>
		<?php $f->textField("params"); ?>
	</div>
	<div class="field">
		<?php $f->label("priority"); ?>
		<?php $f->textField("priority"); ?>
	</div>
	<div class="actions">
		<?php $f->submit('Save'); ?>
	</div>
	
<?php }); ?>