<?php echo $this->formTag((!empty($this->module) && $this->module->id > 0) ? 
		"/admin/modules/" . $this->module->id : "/admin/modules", 
		["class" => "", 'enctype' => 'multipart/form-data', "method" => "POST"], function() { ?>

	<?php if ($this->module->errors && $this->module->errors->count()): ?>
		<div id="error_explanation">
			<?php echo $this->element('h3', "Errors prohibited this module from beign saved:"); ?>
		</div>
		<ul>
			<?php foreach($this->module->errors as $error): ?>
				<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if (!empty($this->module) && $this->module->id > 0): ?>
		<?php echo $this->hiddenFieldTag("module.id", $this->module->id); ?>
		<?php echo $this->hiddenFieldTag("_method", "PUT"); ?>
	<?php endif; ?>
	<fieldset>
		<div class="control-group">
			<?php echo $this->labelTag("module"); ?>
			<?php echo $this->fileFieldTag("module"); ?>
		</div>
		<div class="form-actions">
			<?php echo $this->submit('Upload', array( "class" => 'btn btn-primary' )); ?>
		</div>
	</fieldset>
	
<?php }); ?>
