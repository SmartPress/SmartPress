<?php $this->formFor(['admin', $this->group], ['class' => 'form-horizontal'], function($f) { ?>

	<?php if ($this->group->errors && $this->group->errors->count()): ?>
		<div id="error_explanation">
			<?php element('h2', "{$this->pluralize($this->group, 'error')} prohibited this group from beign saved:"); ?>
		</div>
		<ul>
			<?php foreach($this->group->errors as $error): ?>
				<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<div class="control-group">
		<?php $f->label("name", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php $f->textField("name"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php $f->label("write_privileges", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php $f->textField("write_privileges"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php $f->label("read_privileges", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php $f->textField("read_privileges"); ?>
		</div>
	</div>
	<div class="form-actions">
		<?php $f->submit('Save', ['class' => 'btn btn-primary']); ?>
		<?php $this->linkTo('Cancel', $this->admin_groups_url(), ['class' => 'btn']); ?>
	</div>
	
<?php }); ?>