<?php $this->formFor(['admin', $this->module], ["class" => "", 'enctype' => 'multipart/form-data'], function($f) { ?>

	<?php if ($this->module->errors && $this->module->errors->count()): ?>
		<div id="error_explanation">
			<?php element('h2', "{$this->pluralize($this->module, 'error')} prohibited this module from beign saved:"); ?>
		</div>
		<ul>
			<?php foreach($this->module->errors as $error): ?>
				<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php $f->hidden("id"); ?>
	<fieldset>
		<div class="control-group">
			<?php $f->label("file"); ?>
			<?php $f->fileField("file"); ?>
		</div>
		<div class="form-actions">
			<?php $f->submit('Save', array( "class" => 'btn btn-primary' )); ?>
		</div>
	</fieldset>
	
<?php }); ?>