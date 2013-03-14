<?php echo $this->formFor(array('admin', $this->config), null, function($f) { ?>

	<?php /*if ($this->config->errors && $this->config->errors->count()): ?>
		<div id="error_explanation">
			<?php echo $this->element('h2', "{$this->pluralize($this->config, 'error')} prohibited this config from beign saved:"); ?>
		</div>
		<ul>
			<?php foreach($this->config->errors as $error): ?>
				<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif;*/ ?>

	<div class="field">
		<?php echo $f->label("name"); ?>
		<?php echo $f->textField("name"); ?>
	</div>
	<div class="field">
		<?php echo $f->label("value"); ?>
		<?php echo $f->textField("value"); ?>
	</div>
	<div class="actions">
		<?php echo $f->submit('Save'); ?>
	</div>
	
<?php }); ?>
