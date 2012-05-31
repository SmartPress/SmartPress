<?php $this->formFor($this->module, array("class" => ""), function($f) { ?>

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
			<?php $f->label("name"); ?>
			<?php $f->textField("name"); ?>
		</div>
		<div class="control-group">
			<?php $f->label("code"); ?>
			<?php $f->textField("code"); ?>
		</div>
		<div class="control-group">
			<?php $f->label("version"); ?>
			<?php $f->textField("version"); ?>
		</div>
		<div class="control-group">
			<?php $f->label("status"); ?>
			<?php $f->textField("status"); ?>
		</div>
		<div class="form-actions">
			<?php $f->submit('Save', array( "class" => 'btn btn-primary' )); ?>
		</div>
	</fieldset>
	
<?php }); ?>