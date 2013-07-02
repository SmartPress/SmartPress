<?php echo $this->formFor(['admin', $this->group], ['class' => 'form-horizontal'], function($f) { ?>

	<?php if ($this->group->errors && $this->group->errors->count()): ?>
		<div id="error_explanation">
			<?php echo $this->element('h2', "{$this->pluralize($this->group, 'error')} prohibited this group from beign saved:"); ?>
		</div>
		<ul>
			<?php foreach($this->group->errors as $error): ?>
				<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<div class="control-group">
		<?php echo $f->label("name", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->textField("name"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $f->label("privilege", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->textField("privilege"); ?>
		</div>
	</div>
	<div class="form-actions">
		<?php echo $f->submit('Save', ['class' => 'btn btn-primary']); ?>
		<?php echo $this->linkTo('Cancel', $this->admin_groups_url(), ['class' => 'btn']); ?>
	</div>
	
<?php }); ?>
